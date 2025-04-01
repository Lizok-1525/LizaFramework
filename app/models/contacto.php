<?php

$head_title = "Contacto, Yelyzaveta Krasnolutska - Periodista";
$head_description = "Curriculum con acceso a contactos de Yelyzaveta Krasnolutska";
$canonical_name = "https://liza.ma-no.es/contacto";

session_start();


if (!$encryption) {
    $encryption = new encryption();
}

$code = $encryption->generateRandomCode();
$_SESSION['CSFR'] = $encryption->encryptSimple($code);
$_CSFR = $encryption->encryptSimple($code);

//echo $code;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf'], $_SESSION['CSFR'])) {
        die('Acceso denegado');
    }

    // Verificar si el token enviado es igual al almacenado en sesión
    if ($_POST['csrf'] !== $_SESSION['CSFR']) {
        die('Error: Token no válido.');
    }
}
