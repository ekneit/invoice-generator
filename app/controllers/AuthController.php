<?php

namespace App\Controllers;

use App\Core\Database;

class AuthController
{
    public function showLogin()
    {
        $error = $_GET['error'] ?? '';
        $title = 'Prisijungimas';

        ob_start();
        include __DIR__ . '/../Views/auth/login.php';
        $content = ob_get_clean();

        include __DIR__ . '/../Views/layout.php';
    }


    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /login");
            exit;
        }

        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            header("Location: /login?error=Užpildykite visus laukus");
            exit;
        }

        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();


        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
            header("Location: /invoice/create");
            exit;
        }

        header("Location: /login?error=Neteisingi prisijungimo duomenys");
        exit;
    }


    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        header("Location: /login");
        exit;
    }
}
