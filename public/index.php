<?php

require_once __DIR__ . '/../vendor/autoload.php';


use App\Core\Router;
use App\Core\Request;

$router = new Router();

$router->get('/', [\App\Controllers\HomeController::class, 'index']);

// invoice routes
$router->get('/invoice/create', [\App\Controllers\InvoiceController::class, 'create']);
$router->post('/invoice/save', [\App\Controllers\InvoiceController::class, 'store']);


$router->resolve(Request::url(), Request::method());
