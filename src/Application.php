<?php

namespace Framework;

use Framework\Http\Middleware;
use Framework\Routing\Router;

class Application {
    static public Router $router;
    static protected Container $container;

    public function __construct()
    {
        static::setContainer(new Container());

        static::bootstrapRouter();
    }

    public static function container(): Container
    {
        return static::$container;
    }

    public static function middleware(array $data): void
    {
        $middleware = static::resolve(Middleware::class);
        $middleware->addMiddleware($data);
    }

    public static function bind($key, $resolver): void
    {
        static::$container->bind($key, $resolver);
    }

    public static function resolve($key): mixed
    {
        return static::$container->resolve($key);
    }

    public static function setContainer($container): void
    {
        static::$container = $container;
    }

    public static function bootstrapRouter(): void
    {
        static::$router = new Router();
    }
}
