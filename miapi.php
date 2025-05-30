<?php
/*$data = [
    "hello"
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);*/

require_once './core/encryption.php'; // si está en otro archivo, asegúrate de incluirlo

global $encryption;
$encryption = new encryption(); // falta el paréntesis y punto y coma

echo $encryption->generarHash("Lizok.1525"); // accede correctamente al método