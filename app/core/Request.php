<?php

namespace App\Core;

class Request
{

    public static function url()
    {
        $rawUrl = $_SERVER['REQUEST_URI'];
        $cleanUrl = preg_replace('#/+#', '/', $rawUrl);
        $url = parse_url($cleanUrl, PHP_URL_PATH);

        return rtrim($url, '/') ?: '/';
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
