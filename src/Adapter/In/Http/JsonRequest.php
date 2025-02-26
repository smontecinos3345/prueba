<?php

namespace App\Adapter\In\Http;

class JsonRequest implements Request
{
    private array $json;
    private array $headers;
    private string $method;
    private string $uri;

    public function __construct(
        array $json = [],
        array $headers = [],
        string $method = 'GET',
        string $uri = '/'
    ) {
        $this->json = $json;
        $this->headers = $headers;
        $this->method = $method;
        $this->uri = $uri;
    }

    public static function fromGlobals(): self
    {
        return new self(
            json_decode(file_get_contents('php://input'), true) ?? [],
            [],
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $_SERVER['REQUEST_URI'] ?? '/'
        );
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->json)) {
            return $this->json[$key];
        }

        return $default;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
