<?php
declare(strict_types = 1);

namespace App\Http\Message;

/**
 * Class Response
 *
 * @package App\Http\Message
 */
class Response
{
    public function __construct(
        private array  $content,
        private string $statusCode,
        private string $statusText,
        private array  $headers = []
    )
    {}

    final public function send(): void
    {
        header('HTTP/1.1 ' . $this->statusCode . ' ' . $this->statusText);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo json_encode($this->content, JSON_THROW_ON_ERROR);
    }

    final public function setContent(array $content): void
    {
        $this->content = $content;
    }

    final public function setStatusCode(string $statusCode, string $statusText = ''): void
    {
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;
    }

    final public function setHttpHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }
}
