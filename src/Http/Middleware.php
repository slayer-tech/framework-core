<?php

namespace Framework\Http;

class Middleware
{
    private static ?Middleware $instance = null;
    protected array $middlewares = [

    ];

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function resolve($key)
    {
        if (!$key) {
            return;
        }

        $middleware = $this->middlewares[$key] ?? false;

        if (!$middleware) {
            throw new \Exception("No matching middleware found for key $middleware");
        }

        $middleware = new $middleware;

        $middleware->handle();
    }

    public function addMiddleware(array $data)
    {
        foreach ($data as $key => $value) {
            $this->middlewares[$key] = $value;
        }
    }
}