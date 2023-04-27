<?php

namespace Framework\Routing;

class RouteCollection
{
    public array $routes = [];

    public function addRoute(string $method, string $uri, \Closure|array $callback): RouteCollection
    {
        $this->routes[$method][] = [
            'uri' => '/' . trim($uri, '/'),
            'callback' => $callback,
            'middleware' => null
        ];

        return $this;
    }

    public function setMiddleware($middleware)
    {
        $method = array_key_last($this->routes);
        $key = array_key_last($this->routes[$method]);

        $this->routes[$method][$key]['middleware'] = $middleware;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}