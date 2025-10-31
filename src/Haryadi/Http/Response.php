<?php

namespace Haryadi\Http;

class Response
{
    public function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    public function setStatusCode(int $code): self
    {
        http_response_code($code);
        return $this;
    }

    public function setHeader(string $key, string $value): self
    {
        header("{$key}: {$value}");
        return $this;
    }
}