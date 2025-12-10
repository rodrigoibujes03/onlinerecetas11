<?php
// config/config.php

// DB
if (!defined('DB_HOST')) define('DB_HOST','127.0.0.1');
if (!defined('DB_USER')) define('DB_USER','root'); // XAMPP default
if (!defined('DB_PASS')) define('DB_PASS','');     // XAMPP default: empty
if (!defined('DB_NAME')) define('DB_NAME','recetasonline');

// App
if (!defined('BASE_URL')) define('BASE_URL','/Recetasonline/public'); // ajusta si tu carpeta difiere
if (!defined('UPLOADS_DIR')) define('UPLOADS_DIR', __DIR__ . '/../public/uploads');
if (!defined('UPLOADS_URL')) define('UPLOADS_URL', BASE_URL . '/uploads');

// Conexión PDO global
try {
    $conn = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
