<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


include_once '../inc/config.inc.php';
global $conn;

$mensaje = $_POST['mensaje'] ?? '';

if (!empty($mensaje)) {
    $stmt = $conn->prepare("INSERT INTO `chat` (`mensaje`) VALUES (?)");
    $stmt->bind_param("s", $mensaje);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT mensaje FROM chat");

while ($row = $result->fetch_assoc()) {
    echo "<div class='alert alert-secondary m-2'>" . htmlspecialchars($row['mensaje']) . "</div>";
}
