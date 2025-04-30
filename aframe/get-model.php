<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


header('Content-Type: application/json');

include_once '../inc/config.inc.php';
global $conn;

$id = intval($_GET['ID'] ?? 0);

$sql = "SELECT url, posicion FROM aframe WHERE ID = $id";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Modelo no encontrado"]);
}
