<?php
declare(strict_types = 1);

namespace App\Routing;

use App\Http\Message\Request;
use App\Http\Message\Response;
use App\Http\Middleware\MiddlewareTrait;
use Psr\Container\ContainerInterface;
use Closure;

/**
 * Class Route
 *
 * @package App\Routing
 */
class Route
{
    use MiddlewareTrait;

    /**
     * Route constructor.
     *
     * @param ContainerInterface $container
     * @param string             $method
     * @param string             $pass
     * @param string|Closure     $handler
     */
    public function __construct(
        private ContainerInterface $container,
        private string             $method,
        private string             $pass,
        private string|Closure     $handler
    )
    {
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

        $handler = is_string($this->handler)
            ? $this->container->get($this->handler)
            : $this->handler;

        return $handler($request, $vars);
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
