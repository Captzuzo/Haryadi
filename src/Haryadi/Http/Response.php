<?php
namespace Haryadi\Http;

class Response
{
    protected string $content;
    protected int $status;
    protected array $headers = [];

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function send(): void
    {
        if (!headers_sent()) {
            http_response_code($this->status);
            foreach ($this->headers as $k => $v) {
                header("$k: $v");
            }
        }

        echo $this->content;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
