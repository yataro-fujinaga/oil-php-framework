<?php
declare(strict_types = 1);

namespace App\Routing;

use App\Http\Controller\Controller;
use App\Http\Message\Request;
use App\Http\Message\Response;
use App\Http\Middleware\MiddlewareTrait;

/**
 * Class Route
 *
 * @package App\Routing
 */
class Route
{
    use MiddlewareTrait;

    private string $method;
    private string $pass;

    /**
     * @var Controller|callable
     */
    private $handler;

    public function __construct(string $method, string $pass, callable|string $handler)
    {
        $this->method  = $method;
        $this->pass    = $pass;
        $this->handler = $this->resolveHandler($handler);
    }

    private function resolveHandler(callable|string $handler): callable|Controller
    {
        if (is_string($handler)) {
            assert(($controller = new $handler) instanceof Controller);
            return $controller;
        }

        return $handler;
    }

    final public function processable(Request $request): bool
    {
        if ($request->getMethod() !== $this->method) {
            return false;
        }

        if (($tokens = $this->createTokens($request)) === []) {
            return false;
        }

        foreach ($tokens as $exploded_uri_pattern => $exploded_uri) {
            if (str_starts_with($exploded_uri_pattern, ':')) {
                continue;
            }

            if ($exploded_uri_pattern !== $exploded_uri) {
                return false;
            }
        }

        return true;
    }

    final public function process(Request $request): Response
    {
        $this->processMiddleware($request);

        $vars = [];

        foreach ($this->createTokens($request) as $exploded_uri_pattern => $exploded_uri) {
            if (str_starts_with($exploded_uri_pattern, ':')) {
                $vars[ltrim($exploded_uri_pattern, ':')] = $exploded_uri;
            }
        }

        return call_user_func($this->handler, $request, $vars);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function createTokens(Request $request): array
    {
        $exploded_uri_patterns = explode('/', ltrim($this->pass, '/'));
        $exploded_uris         = explode('/', ltrim($request->getPathInfo(), '/'));

        if (count($exploded_uri_patterns) !== count($exploded_uris)) {
            return [];
        }

        return array_combine($exploded_uri_patterns, $exploded_uris);
    }
}
