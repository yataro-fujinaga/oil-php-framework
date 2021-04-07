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
    private array $middlewares = [
        SampleGlobalMiddleware::class
    ];

    public function __construct(private Router $router) {}

    final public function handle(Request $request): Response
    {
        try {
            // add global middleware.
            $this->router->middleware($this->middlewares);

            return $this->router->dispatch($request);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}
