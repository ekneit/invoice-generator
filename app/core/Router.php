<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function resolve($uri, $method)
    {
        $url = rtrim($uri, '/') ?: '/';

        $action = $this->routes[$method][$url] ?? false;

        if (!$action) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        [$controller, $method] = $action;

        call_user_func([$controller, $method]);
    }
}
