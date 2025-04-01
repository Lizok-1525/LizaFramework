<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
$head_title = "Testing";

$items_por_pagina = 25; // Cantidad de elementos por página

$pagina_actual = (int)$_GET['pagina'];
if ($pagina_actual == 0) {
    $pagina_actual = 1;
}
//$pagina_actual = max(1, $pagina_actual);
$user_id = $_SESSION["ID"];

$stmt = $conn->prepare("SELECT public_key_api FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($clave);
$stmt->fetch();
$stmt->close();

// 🔹 Calcular el índice de inicio
$indice_inicio = ($pagina_actual - 1) * $items_por_pagina;

// 🔹 Obtener los elementos de la página actual

$url = "https://liza.ma-no.es/api/usuarios?fr=$indice_inicio&st=$items_por_pagina";


// Inicializar cURL

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLINFO_HEADER_OUT, true);

/**/
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/json';
$headers[] = 'X-API-Key: ' . $clave . '';

// Establecer los encabezados en la solicitud
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

//$information = curl_getinfo($curl);

$response = curl_exec($curl);
curl_close($curl);

// Convertir JSON a array asociativo
$usuarios = json_decode($response, true);

/*
global $core;
$core->print_arr($usuarios);
*/

$total_items = $usuarios['navigation']['of']; // Total de elementos en la API
$total_paginas = ceil($usuarios['navigation']['of'] / $items_por_pagina); // Calcular total de páginas


// Número de botones a mostrar alrededor de la página actual
$botones_a_mostrar = 10;

// Calcular el inicio y el fin de los botones a mostrar
$inicio_botones = max(1, $pagina_actual - floor($botones_a_mostrar / 2));
$fin_botones = min($total_paginas, $pagina_actual + floor($botones_a_mostrar / 2));

// Ajustar el inicio si el fin alcanza el total de páginas
$inicio_botones = max(1, $fin_botones - $botones_a_mostrar + 1);
