<?php

namespace CGRD\Core;

use ArrayIterator;
use Exception;
use CGRD\Http\RequestInterface;

class Router
{
    private ArrayIterator $routes;

    public function __construct(array $routes)
    {
        $this->routes = new ArrayIterator();

        foreach ($routes as $route) {
            $this->add($route);
        }
    }

    public function add($route): self
    {
        $route = new Route($route[0], $route[1], $route[2], $route[3]);
        $this->routes->offsetSet($route->getName(), $route);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function match(RequestInterface $serverRequest): Route
    {
        return $this->matchFromPath($serverRequest->getUri(), $serverRequest->getMethod());
    }

    /**
     * @throws Exception
     */
    public function matchFromPath(string $path, string $method): Route
    {               
        foreach ($this->routes as $route) {
            if ($route->match($path, $method) === false) {
                continue;
            }
            return $route;
        }        

        throw new Exception(
            'No route found for ' . $method,
            404
        );
    }

}
