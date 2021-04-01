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
        $script_name = $_SERVER['SCRIPT_NAME'];
        $request_uri = $this->getRequestUri();

        if (str_starts_with($request_uri, $script_name)) {
            return $script_name;
        }

        if (str_starts_with($request_uri, dirname($script_name))) {
            return rtrim(dirname($script_name), '/');
        }

        return '';
    }

    final public function getPathInfo(): string
    {
        $base_url    = $this->getBaseUrl();

        $request_uri = explode('?', $this->getRequestUri())[0];

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
