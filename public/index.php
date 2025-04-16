<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Router;
use App\Core\Request;
use App\Core\Auth;

session_start();


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$router = new Router();


$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/login', [\App\Controllers\AuthController::class, 'showLogin']);
$router->post('/login', [\App\Controllers\AuthController::class, 'login']);
$router->get('/register', [\App\Controllers\AuthController::class, 'showRegister']);
$router->post('/register', [\App\Controllers\AuthController::class, 'register']);



$protectedRoutes = [
    '/logout',
    '/invoices',
    '/invoice/create',
    '/invoice/save',
    '/invoice/view/{id}',
    '/invoice/update/{id}',
    '/upload',

];

$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
foreach ($protectedRoutes as $protected) {
    if (str_starts_with($currentUrl, $protected) && !Auth::check()) {
        header("Location: /login");
        exit;
    }
}

$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);
$router->get('/invoices', [\App\Controllers\InvoiceController::class, 'invoices']);
$router->get('/invoice/create', [\App\Controllers\InvoiceController::class, 'create']);
$router->get('/invoice/view/{id}', [\App\Controllers\InvoiceController::class, 'show']);

$router->post('/invoice/save', [\App\Controllers\InvoiceController::class, 'store']);
$router->post('/invoice/update/{id}', [\App\Controllers\InvoiceController::class, 'update']);

$router->get('/upload', [\App\Controllers\UploadController::class, 'showForm']);
$router->post('/upload', [\App\Controllers\UploadController::class, 'handleUpload']);





$router->resolve(Request::url(), Request::method());
