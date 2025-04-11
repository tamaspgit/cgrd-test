<?php

namespace CGRD\Core;

use CGRD\Core\Container;
use CGRD\Database\DatabaseInterface;
use CGRD\Http\RequestInterface;
use CGRD\Http\ResponseInterface;

class Application {

    private Container $container;

    private RequestInterface $request;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->request = $this->container->resolve(RequestInterface::class);            
    }

    public function run(): void
    {            
        $route = $this->createRoute($this->request);

        $controller = $route->getController();

        $action = $route->getAction();      
        
        $controller = new $controller(
            $this->container,
            $this->request,
            $this->container->resolve(ResponseInterface::class),
            $this->container->resolve('renderer'),
            $this->container->resolve(DatabaseInterface::class),
            $this->container->resolve(Session::class)
        );    
        
        $response = $controller->$action($route->getVars());

        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $value) {
            header("$name: $value");
        }

        echo $response->getContents();
    }

    private function createRoute(RequestInterface $request): Route
    {   
        $router = $this->container->resolve('router');

        return $router->match($request);
    }    
}