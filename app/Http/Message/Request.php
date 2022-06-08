<?php
declare(strict_types = 1);

namespace App\Http\Message;

/**
 * Class Request
 *
 * @package App\Http\Message
 */
class Request
{
    public function isSsl(): bool
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }

    public function getHost(): string
    {
        return $_SERVER['SERVER_NAME'];
    }

    final public function getBaseUrl(): string
    {
        // $_SERVERはサーバーの情報を保持する
        // $_SERVER['SCRIPT_NAME']はスクリプトの絶対パスを返す
        $script_name = $_SERVER['SCRIPT_NAME'];

        // ページにアクセスするために指定されたURIを取得
        $request_uri = $this->getRequestUri();

        // リクエストされたURIがスクリプト名から始まるなら
        // そのスクリプト名を返す
        if (str_starts_with($request_uri, $script_name)) {
            return $script_name;
        }

        // リクエストされたURIがディレクトリを含むスクリプト名から始まるなら
        // ディレクトリを含むスクリプト名を返す
        if (str_starts_with($request_uri, dirname($script_name))) {
            return rtrim(dirname($script_name), '/');
        }

        return '';
    }

    final public function getPathInfo(): string
    {
        // スクリプト名を指定
        $base_url    = $this->getBaseUrl();

        // URIからparameterを除いたURIを取得
        $request_uri = explode('?', $this->getRequestUri())[0];

        // スクリプト名以降のURIを取得
        return (string) substr($request_uri, strlen($base_url));
    }

    public function getRequestUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getQueryParams(): array
    {
        return $_GET;
    }

    public function getRequestBody(): array
    {
        return $_POST;
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
