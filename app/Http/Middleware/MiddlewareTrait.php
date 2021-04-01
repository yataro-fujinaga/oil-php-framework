<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Http\Message\Request;

trait MiddlewareTrait
{
    /**
     * @var string[]
     */
    private array $middlewares = [];

    /**
     * Add middleware.
     *
     * @param string|array $middleware
     *
     * @return $this
     */
    final public function middleware(string|array $middleware): self
    {
        if (is_array($middleware)) {
            $this->middlewares = array_unique(array_merge($this->middlewares, $middleware));
        }

        if (is_string($middleware)) {
            $this->middlewares = array_unique(array_merge($this->middlewares, [$middleware]));
        }

        return $this;
    }

    private function processMiddleware(Request $request): void
    {
        foreach ($this->middlewares as $middleware) {
            (new $middleware)->process($request);
        }
    }
}