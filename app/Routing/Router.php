<?php
declare(strict_types = 1);

namespace App\Routing;

use App\Http\Controller\Exception\NotFoundException;
use App\Http\Message\Request;
use App\Http\Message\Response;
use App\Http\Middleware\MiddlewareTrait;
use Closure;
use Psr\Container\ContainerInterface;

/**
 * Class Router
 *
 * @package App\Routing
 */
class Router
{
    use MiddlewareTrait;

    /**
     * @var Route[]
     */
    private array $routes = [];

    /**
     * Router constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(private ContainerInterface $container) {}

    /**
     * @param string         $method
     * @param string         $pass
     * @param Closure|string $handler
     *
     * @return Route
     */
    final public function map(string $method, string $pass, Closure|string $handler): Route
    {
        // $this->containerはClassのInstance化のための変数
        // $methodはHTTPリクエストのメソッド（GET、POSTなど）
        // $passはURIのパス（/index、/articles/createなど）
        // $handlerは実行する処理(Closure、Controllerで指定）
        $route = new Route($this->container, $method, $pass, $handler);

        $this->routes[] = $route;

        return $route;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    final public function dispatch(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if ($route->processable($request)) {
                // Merge root middleware and global middleware.
                // routeに適用したいmiddlewareを登録
                $route->middleware($this->middlewares);

                // requestとパラメータの情報を基にRoutingに対応した処理を実行
                return $route->process($request);
            }
        }

        throw new NotFoundException('404 Not Found.');
    }
}
