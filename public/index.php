<?php

require_once __DIR__ . '/../vendor/autoload.php';

start_session();

use Dotenv\Dotenv;
use App\Core\Router;
use App\Core\Request;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$router = new Router();

$router->get('/', [\App\Controllers\HomeController::class, 'index']);

// invoice routes
$router->get('/invoice/create', [\App\Controllers\InvoiceController::class, 'create']);
$router->post('/invoice/save', [\App\Controllers\InvoiceController::class, 'store']);


$router->resolve(Request::url(), Request::method());
