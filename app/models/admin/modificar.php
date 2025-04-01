<?php

$head_title = "Modificacion de noticias";
$head_description = "Modificacion de noticias";


if (!isset($last_param) || empty($last_param)) {
  die("ID de noticia no proporcionado.");
}

$id = intval($last_param);

// Obtener los datos de la noticia
$result = $conn->query("SELECT * FROM noticias WHERE ID = $last_param");
if ($result->num_rows == 0) {
  die("Noticia no encontrada.");
}

$noticia = $result->fetch_assoc();

// Si el formulario se envió, actualizar la noticia
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $titulo = $conn->real_escape_string($_POST['titulo']);
  $contenido = $conn->real_escape_string($_POST['contenido']);

  $update = $conn->query("UPDATE noticias SET titulo = '$titulo', contenido = '$contenido' WHERE ID = $id");


  if ($update) {
    echo "<p class='alert alert-success'>Noticia actualizada correctamente.</p>";
    // Redireccionar después de actualizar para evitar el reenvío del formulario
    header("Location: index");
    exit();
  } else {
    echo "<p class='alert alert-danger'>Error al actualizar: " . $conn->error . "</p>";
  }
}
