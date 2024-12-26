<?php

namespace OlympiaWorkout\bootstrap\Router;

class Router
{
    protected static array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public static function get(string $uri, string $handler): void
    {
        self::$routes['GET'][self::treatUri($uri)] = $handler;
    }

    public static function post(string $uri, string $handler): void
    {
        self::$routes['POST'][self::treatUri($uri)] = $handler;
    }

    private static function treatUri(string $uri): string
    {
        $base = trim(dirname($_SERVER['SCRIPT_NAME']), '/');

        $parsedUri = parse_url($uri, PHP_URL_PATH);
        $relativeUri = trim(str_replace($base, '', $parsedUri), '/');

        return $relativeUri === '' ? '/' : $relativeUri;
    }
    
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    private static function splitController(string $handler): array
    {
        return explode('@', $handler);
    }

    public static function resolve(Request $request): void
    {
        $uri = self::treatUri($request->uri());
        $method = $request->method();

        if (!isset(self::$routes[$method][$uri])) {
            $request->response(404, "No route found for URI '{$uri}'.");
        }

        $handler = self::$routes[$method][$uri];
        [$controller, $method] = self::splitController($handler);

        $controllerClass = "OlympiaWorkout\\HTTP\\Controllers\\{$controller}";

        if (!class_exists($controllerClass)) {
            $request->response(500, "Controller '{$controller}' not found for URI '{$uri}'.");
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $method)) {
            $request->response(500, "Method '{$method}' not found in '{$controllerClass}' for URI '{$uri}'.");
        }

        $data = $request->data();
        $controllerInstance->{$method}(...($data ? [$data] : []));
    }
}
