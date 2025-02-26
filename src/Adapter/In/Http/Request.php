<?php

namespace App\Adapter\In\Http;

interface Request
{
    public function get(string $name, mixed $default = null);

    public function getMethod(): string;

    public function getUri(): string;
}
