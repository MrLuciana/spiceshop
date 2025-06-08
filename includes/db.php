<?php
$host = 'localhost';
$dbname = 'spiceshop_db';
$user = 'mrlu_spiceshop';
$pass = '597$qj6rW';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
