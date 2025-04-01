<?php

$head_title = "Yelyzaveta Krasnolutska";
$head_description = "Pagina de administracion de curriculum de Yelyzaveta Krasnolutska";


include 'inc.check-user.php';

if (!$encryption) {
  $encryption = new encryption();
}



$usuario = $_SESSION["usuario"];
$stmt = $conn->prepare("SELECT email, private_key_api, public_key_api FROM users WHERE name = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
  $email = $user["email"];
} else {
  $email = "No encontrado";
}


$user_id = $_SESSION['ID'];

$stmt = $conn->prepare("SELECT private_key_api, public_key_api FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($private_key_api, $public_key_api);
$stmt->fetch();
$stmt->close();



if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $private_key_api = $encryption->generateRandomCode(10);
  $public_key_api = $encryption->encryptSimple($private_key_api);

  // Actualizar las claves en la base de datos
  $stmt = $conn->prepare("UPDATE users SET private_key_api = ?, public_key_api = ? WHERE id = ?");
  $stmt->bind_param("ssi", $private_key_api, $public_key_api, $user_id);


  if ($stmt->execute()) {
  } else {
    echo "❌ Error al actualizar claves: " . $stmt->error;
    error_log("Error en UPDATE: " . $stmt->error);
  }
  $stmt->close();
}


$core = new core();
$stmt->close();
