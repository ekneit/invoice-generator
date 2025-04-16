<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function get($url, $action)
    {
        $this->routes['GET'][$url] = $action;
    }

    public function post($url, $action)
    {
        $this->routes['POST'][$url] = $action;
    }

    public function resolve($url, $method)
    {
        $url = rtrim($url, '/') ?: '/';
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route => $action) {
            if ($route === $url) {
                [$controller, $method] = $action;
                $instance = new $controller();
                return call_user_func([$instance, $method]);
            }

            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $pattern = "#^" . rtrim($pattern, '/') . "$#";

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);

                [$controller, $method] = $action;
                $instance = new $controller();
                return call_user_func_array([$instance, $method], $matches);
            }
        }

        http_response_code(404);
        echo '404 Not Found';
        exit;
    }
}
