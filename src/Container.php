<?php

namespace Framework;

class Container
{
    protected $bindings = [];

    public function bind($key, $resolver) : void
    {
        $this->bindings[$key] = $resolver;
    }

    public function resolve($key) : mixed
    {
        if (array_key_exists($key, $this->bindings)) {
            $resolver = $this->bindings[$key];

            return call_user_func($resolver);
        }

        throw new \Exception("No matching binding for $key");
    }
}