<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\BaseController;

/* 
TODO: Important!
-  CSRF Protection
-  Rate Limiting
-  Session Regeneration
-  Check ALL Input Sanitization / Type Checks
-  User Activation
-  Password Reset
-  User Session Management
*/

class AuthController extends BaseController
{
    public function showLogin()
    {
        $this->view('auth/login', [
            'title' => 'Prisijungimas',
            'error' => $_GET['error'] ?? '',
            'success' => $_GET['success'] ?? ''
        ]);
    }
    public function showRegister()
    {
        $this->view('auth/register', [
            'title' => 'Registracija',
            'error' => $_GET['error'] ?? '',
            'success' => $_GET['success'] ?? ''
        ]);
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
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /register");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        // TODO: Change validation and error handling
        if (!$name || !$email || !$password) {
            header("Location: /register?error=Užpildykite visus laukus");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: /register?error=Neteisingas el. pašto adresas");
            exit;
        }

        if (strlen($password) < 6) {
            header("Location: /register?error=Slaptažodis per trumpas (min. 6 simboliai)");
            exit;
        }

        $pdo = \App\Core\Database::connect();

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            header("Location: /register?error=Šis el. paštas jau naudojamas");
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

        $stmt->execute([$name, $email, $hashedPassword]);


        $userId = $pdo->lastInsertId();

        $_SESSION['user'] = [
            'id' => $userId,
            'name' => $name,
            'email' => $email
        ];

        header("Location: /invoice/create");
        exit;
    }
}
