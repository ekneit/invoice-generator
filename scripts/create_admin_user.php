

<?php
// php scripts/create_admin_user.php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

// Inicijuojam .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$pdo = Database::connect();

$email = 'admin@example.com';
$password = 'admin123';
$hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    echo "✅ Vartotojas jau egzistuoja: $email\n";
} else {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute(['Administratorius', $email, $hashed]);
    echo "✅ Sukurtas naujas administratorius su el. paštu: $email\n";
}
