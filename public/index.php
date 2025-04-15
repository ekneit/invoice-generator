<?php

require_once __DIR__ . '/../vendor/autoload.php';


use App\Core\Router;
use App\Core\Request;

$router = new Router();

$router->get('/', [\App\Controllers\HomeController::class, 'index']);


$router->resolve(Request::url(), Request::method());
