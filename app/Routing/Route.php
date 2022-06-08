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
            // URIにパラメータが存在するなら
            // パラメータ名とURIをセットにして配列に保存
            if (str_starts_with($exploded_uri_pattern, ':')) {
                $vars[ltrim($exploded_uri_pattern, ':')] = $exploded_uri;
            }
        }

        // Closureで指定されているならClosureを
        // Containerで指定されているならContainerをInstance化
        $handler = is_string($this->handler)
            ? $this->container->get($this->handler)
            : $this->handler;

        // requestとパラメータの情報を基にRoutingに対応した処理を実行
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

        // requestされたuriとframeworkで指定したパスが同じなら
        // tokenはなし
        if (count($exploded_uri_patterns) !== count($exploded_uris)) {
            return [];
        }

        // requestされたuriとframeworkで指定したパスが異なるなら
        // requestされたuriとframeworkで指定したパスをセットにして配列を返す
        return array_combine($exploded_uri_patterns, $exploded_uris);
    }
}
