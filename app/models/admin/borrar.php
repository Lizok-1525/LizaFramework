<?php

include 'admin/inc.check-user.php';

if (!isset($last_param) || empty($last_param)) {
  die("ID de noticia no proporcionado.");
}

$id = intval($last_param);

$sql = "DELETE FROM noticias WHERE ID=$id";

if ($conn->query($sql) === TRUE) {
  echo "Record deleted successfully";
  header("Location: index");
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();
