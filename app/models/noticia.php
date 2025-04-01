<?php


$head_title = "Noticia:";
$head_description = "Noticia de Yelyzaveta Krasnolutska";
$canonical_name = "https://liza.ma-no.es/noticias/";

$id = $last_param;

if (!$core) {
    $core = new core();
}

$result = $conn->query("SELECT ID, titulo, contenido FROM noticias WHERE ID=$id");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $head_title = "Noticia: " . $row['titulo']; // Construir $head_title aquí
    // Ahora puedes usar $row['contenido'] para otras cosas.
    $canonical_name = "https://liza.ma-no.es/noticias/" . $core->generarSlug($row['titulo']) . "-" . $row['ID'];
} else {
    // Si no se encuentra la noticia, puedes asignar un título predeterminado.
    $head_title = "Noticia no encontrada";
}
