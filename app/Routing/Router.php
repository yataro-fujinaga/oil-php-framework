<?php
declare(strict_types = 1);

namespace App\Routing;

use App\Http\Message\Request;
use App\Http\Message\Response;
use App\Http\Middleware\MiddlewareTrait;

/**
 * Class Router
 *
 * @package App\Routing
 */
class Router
{
    use MiddlewareTrait;

    public function __construct(private array $routes = []) {}

    /**
     * @param string          $method
     * @param string          $pass
     * @param string|callable $handler
     *
     * @return Route
     */
    final public function map(string $method, string $pass, callable|string $handler): Route
    {
        $route = new Route($method, $pass, $handler);

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
                $route->middleware($this->middlewares);

                return $route->process($request);
            }
        }
    }
}
