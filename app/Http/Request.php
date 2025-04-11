<?php

namespace CGRD\Http;

class Request implements RequestInterface {

    public const METHOD_POST = 'POST';

    public const METHOD_GET = 'GET';

    public const METHOD_PUT = 'PUT';

    public const METHOD_DELETE = 'DELETE';

    private readonly array $server;

    private readonly array $get;

    private readonly array $post;

    public function __construct()
    {
        $this->server = $_SERVER;        
        $this->get = $_GET;
        $this->post = $_POST;
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getUri() 
    {
        return $this->server['REQUEST_URI'];
    }

    public function getParam($key, $default = null)
    {
        return $this->get[$key] ?? $this->post[$key] ?? $default;
    }

    public function isPost(): bool
    {
        return $this->getMethod() === self::METHOD_POST;
    }

    public function getPost(): array
    {
        return $this->post;
    }
}