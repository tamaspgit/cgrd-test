<?php

namespace CGRD\Http;

class Response implements ResponseInterface {

    private array $headers = [];

    private int $statusCode = 200;

    public function __construct(private ResponseStreamInterface $body) {}
   
    public function getBody()
    {
        return $this->body;
    }

    public function getContents(): string
    {
        return $this->body->getContents();
    }

    public function addHeader(string $name, string $value): void
    {
        if (isset($this->headers[$name])) {            
            if (!is_array($this->headers[$name])) {
                $this->headers[$name] = [$this->headers[$name]];
            }
            $this->headers[$name][] = $value;
        } else {
            $this->headers[$name] = $value;
        }
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

}