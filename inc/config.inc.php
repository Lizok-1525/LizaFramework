<?php
session_start(); // Iniciar sesión


define('BASE_PATH', dirname(__DIR__) . '/');

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // Conexión local
    $servername = 'localhost';
    $username = 'root';
    $password = 'root';
    $database = 'c1_liza';
} else {
    // Conexión en servidor online
    $servername = 'localhost';
    $username = 'c1_liza';
    $password = 'wfN4mC!UJ4pg';
    $database = 'c1_liza';
}

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


$template_path = BASE_PATH . "/template/standard/template.php";
