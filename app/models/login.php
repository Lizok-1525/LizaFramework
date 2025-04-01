<?php

$head_title = "Iniciar Sesión";
$head_description = "Login para entrar en paginas privadas";


session_start();

if (!$encryption) {
    $encryption = new encryption();
}


$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Comprobar si los campos están vacíos
    if (empty($email) || empty($password)) {
        $error = "⚠️ Todos los campos son obligatorios.";
    } else {
        // Consulta segura con prepared statements para evitar SQL Injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    //$clave = $encryption->decryptSimple($user["password"]);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        //$clave = $encryption->decryptSimple($user["password"]);
        if ($encryption->verificarHash($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION["usuario"] = $user["name"]; // Guardar el nombre en la sesión
            $_SESSION["ID"] = $user["ID"];
            header("Location: admin/index"); // Redirigir a la página principal
            exit();
        } else {
            echo "⚠️ Usuario o contraseña incorrectos.";
        }
    } else {
        echo "⚠️ Usuario o contraseña incorrectos.";
    }
    $stmt->close();
}



$conn->close();
