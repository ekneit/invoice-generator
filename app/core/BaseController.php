<?php

namespace App\Core;

class BaseController
{

    public function view($view, $data = [])
    {
        extract($data);

        ob_start();

        require __DIR__ . "/../Views/{$view}.php";

        $content = ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }
}
