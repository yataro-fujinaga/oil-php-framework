<?php
declare(strict_types = 1);

namespace App\Http;

use App\Http\Message\Request;
use App\Http\Message\Response;
use App\Routing\Router;

class HttpKernel
{
    private array $middlewares = [
    ];

    public function __construct(private Router $router) {}

    final public function handle(Request $request): Response
    {
        try {
            return $this->router->middleware($this->middlewares)->dispatch($request);
        } catch (\Exception $exception) {
            echo 'Request Failed.';
        }
    }
}
