<?php

namespace App\Entity;

class Payment
{
    private string $method;

    public function __construct(string $method)
    {
        $this->method = $method;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}