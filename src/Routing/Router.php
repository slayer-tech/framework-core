<?php

namespace Framework\Routing;

use Framework\Http\Middleware;
use Framework\Routing\RouteCollection;
use Framework\Application;

class Router
{
    protected array $middlewares = [];
    protected RouteCollection $routes;

    public function __construct() {
        $this->routes = new RouteCollection();
    }

    public function __destruct()
    {
        $this->run();
    }

    public function run(): void
    {
        $routes = $this->routes->getRoutes()[$_SERVER['REQUEST_METHOD']];

        if (isset($routes)) {
            $this->resolve($routes);

            return;
        }
    }

    public function resolve(array $routes): void
    {
        foreach ($routes as $route) {
            $uri = '/' . trim($_SERVER['REQUEST_URI'], '/');
            if (strpos($uri, "?")) {
                $uri = substr($uri, 0, strpos($uri, "?"));
            }
            $route['uri'] = preg_replace('/\/{(.*?)}/', '/(.+)', $route['uri']);

            if (preg_match_all('#^' . $route['uri'] . '$#', $uri, $matches, PREG_OFFSET_CAPTURE)) {
                if ($route['middleware']) {
                    $middleware = Application::resolve(Middleware::class);
                    $middleware->resolve($route['middleware']);
                }

                $matches = array_slice($matches, 1);

                $params = array_map(function ($match, $index) use ($matches) {
                    if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                        return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                    }

                    return isset($match[0][0]) ? trim($match[0][0], '/') : null;
                }, $matches, array_keys($matches));

                $this->invoke($route['callback'], $params);
            }
        }
    }

    public function invoke(\Closure|array $callback, $params): void
    {
        $response = null;

        if (is_callable($callback)) {
            $response = (new Call())->anonymous($callback, $params);
        }
        else {
            $response = (new Call())->action($callback, $params);
        }

        Renderer::render($response);
    }

    public function only(string $key)
    {
        $this->routes->setMiddleware($key);
    }

    public function head(string $uri, \Closure|array $callback)
    {
        $this->routes->addRoute('HEAD', $uri, $callback);

        return $this;
    }

    public function get(string $uri, \Closure|array $callback)
    {
        $this->routes->addRoute('GET', $uri, $callback);

        return $this;
    }

    public function post(string $uri, \Closure|array $callback)
    {
        $this->routes->addRoute('POST', $uri, $callback);

        return $this;
    }

    public function put(string $uri, \Closure|array $callback)
    {
        $this->routes->addRoute('PUT', $uri, $callback);

        return $this;
    }

    public function patch(string $uri, \Closure|array $callback)
    {
        $this->routes->addRoute('PATCH', $uri, $callback);

        return $this;
    }

    public function delete(string $uri, \Closure|array $callback)
    {
        $this->routes->addRoute('DELETE', $uri, $callback);

        return $this;
    }

    public function options(string $uri, \Closure|array $callback)
    {
        $this->addRoute('OPTIONS', $uri, $callback);

        return $this;
    }
}