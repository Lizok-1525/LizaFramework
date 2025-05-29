<?php
//header("Access-Control-Allow-Origin: http://localhost"); // o solo 
$contenido = file_get_contents("php://input");

// Solo permitir "0" o "1"
if ($contenido !== "1" && $contenido !== "0") {
    http_response_code(400);
    echo "Entrada no válida";
    exit;
}

// Escribir en orden.txt
file_put_contents("orden.txt", $contenido);
echo "OK";
