<?php
header('Content-Type: application/json');

include_once '../inc/config.inc.php';
global $conn;

$sql = "SELECT url, posicion FROM aframe";
$result = $conn->query($sql);

$modelos = [];

while ($row = $result->fetch_assoc()) {
    $modelos[] = $row;
}

echo json_encode($modelos);
