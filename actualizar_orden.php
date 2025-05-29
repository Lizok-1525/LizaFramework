<?php
if (isset($_GET['estado'])) {
    $estado = $_GET['estado'];
    if ($estado === "1" || $estado === "0") {
        file_put_contents('orden.txt', $estado);
        echo "OK";
    } else {
        echo "Valor inválido";
    }
} else {
    echo "No se recibió estado";
}
