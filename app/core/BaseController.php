<?php

namespace App\Core;

class BaseController
{

    public function view($view, $data = [])
    {
        extract($data);

        ob_start();

        require __DIR__ . "/../views/{$view}.php";

        $content = ob_get_clean();

        require __DIR__ . '/../views/layouts/app.php';
    }
}
