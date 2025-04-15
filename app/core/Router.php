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

        $action = $this->routes[$method][$url] ?? null;

        if (!$action) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        [$controller, $method] = $action;
        $instance = new $controller();
        call_user_func([$instance, $method]);
    }
}
