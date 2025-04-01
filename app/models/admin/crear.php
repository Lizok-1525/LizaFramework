<?php

include 'admin/inc.check-user.php';

$head_title = "Creacion de noticias";
$head_description = "Creacion de noticias";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $titulo = $conn->real_escape_string($_POST['titulo']);
  $contenido = $conn->real_escape_string($_POST['contenido']);


  $sql = "INSERT INTO noticias (titulo, contenido, fecha) VALUES ('$titulo', '$contenido', NOW())";
  if ($conn->query($sql) === TRUE) {
    echo "<div class='alert alert-success'>Noticia creada con éxito</div>";
    header("Location: index");
    exit();
  } else {
    echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
  }
}
