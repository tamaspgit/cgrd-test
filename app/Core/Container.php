<?php

namespace CGRD\Core;

use ReflectionClass;

class Container {

    private $bindings = [];

    private $instances = [];
    
    public function bind($key, $resolver)
    {
        $this->bindings[$key] = $resolver;
    }

    public function resolve($key)
    {
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }
    
        if (isset($this->bindings[$key])) {
            $instance = $this->bindings[$key]($this);
        } else {
            $instance = $this->autoResolve($key);
        }
    
        $this->instances[$key] = $instance;
    
        return $instance;
    }

    private function autoResolve($key)
    {        
        $reflection = new ReflectionClass($key);

        if (!$reflection->isInstantiable()) {
            throw new \Exception("Class {$key} is not instantiable.");
        }

        $constructor = $reflection->getConstructor();
        
        if (!$constructor) {
            return new $key();
        }

        $parameters = $constructor->getParameters();
        $dependencies = array_map(fn($param) => $this->resolve($param->getType()->getName()), $parameters);

        return $reflection->newInstanceArgs($dependencies);
    }

    public function loadBindings(array $bindings)
    {
        foreach ($bindings as $key => $resolver) {
            $this->bind($key, $resolver);
        }
    }
}
