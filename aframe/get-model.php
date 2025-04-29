<?php
include_once '../inc/config.inc.php';
global $conn;

$resultado = $conexion->query("SELECT modelo, nombre,  posicion FROM aframe");

$modelos = [];
while ($fila = $resultado->fetch_assoc()) {
    $modelos[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($modelos);
