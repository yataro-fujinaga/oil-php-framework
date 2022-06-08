<?php
declare(strict_types = 1);

namespace App\Http;

use App\Http\Message\Request;
use App\Http\Message\Response;
use App\Http\Middleware\SampleGlobalMiddleware;
use App\Routing\Router;

/**
 * Class HttpKernel
 *
 * @package App\Http
 */
class HttpKernel
{
    // アプリケーション全体に適用したいMiddlewareの登録
    /**
     * global middlewares.
     *
     * @var string[]
     */
    private array $middlewares = [
        SampleGlobalMiddleware::class
    ];

    public function __construct(private Router $router) {}

    final public function handle(Request $request): Response
    {
        // add global middleware.
        // アプリケーション全体に適用したいMiddlewareの適用
        $this->router->middleware($this->middlewares);

        return $this->router->dispatch($request);
    }
}
