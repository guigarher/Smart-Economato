<?php
// db.php — conexión reutilizable
$host = '127.0.0.1';   // o 'localhost'
$db   = 'economato';
$user = 'root';
$pass = '';            // en XAMPP suele estar vacío
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  exit('Error de conexión: ' . $e->getMessage());
}
