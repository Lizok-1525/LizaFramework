<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
if (!$encryption) {
    $encryption = new encryption();
}



//$clave = $private_key_api;
//$pasw = $encryption->encryptSimple($clave);
//echo $pasw;
//$pasw = $encryption->encryptSimple("lizok.1525");
//echo $pasw;



//$headers = getallheaders();
global $core;
//$core->print_arr($_SERVER);

//ZjJPYALXxhPTFUQaxgZOI0ttdElJYWJCNjJIMXMyc3hkREZRRmc9PQ==

$key = $_GET['key'];
if (!$key) {
    $key = $_SERVER['HTTP_X_API_KEY'];
}

if ($key == '') {
    echo '{no-key}' . $_SERVER['HTTP_X_API_KEY'];
    exit;
} else {

    $key = $encryption->decryptSimple($key);


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    /**/

    global $conn;

    //$query = "SELECT * FROM users WHERE private_key_api = :private_key_api";
    $stmt = $conn->prepare("SELECT * FROM users WHERE private_key_api = ?");
    // Preparar la consulta
    $stmt->bind_param("s", $key);
    // Ejecutar la consulta
    // Ejecutar la consulta
    if (!$stmt->execute()) {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }
    $result = $stmt->get_result();



    if ($result->num_rows > 0) {
        // Si se encontró el usuario, mostrar los datos
        while ($usuario = $result->fetch_assoc()) {
            //hay usuario
        }
    } else {
        echo "No se encontró ningún usuario con ese nombre.";
        echo '{invalid-key}:' . $user['private_key_api'];
        exit;
    }
}


/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$template_path = BASE_PATH . "/template/api/template_json.php";

//print_r($segments[1]);


switch ($segments[1]) {
    case "emails":


        $jsonResult = [];
        $jsonResult['navigation']['fr'] = '0';
        $jsonResult['navigation']['to'] = '20';
        $jsonResult['navigation']['st'] = '20';

        $sql = "SELECT * FROM emails";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $jsonResult['content'][] = $row;
            }
        }
        break;
    case "usuarios":
        $jsonResult = [];

        $jsonResult['navigation']['fr'] = '0';
        $jsonResult['navigation']['to'] = '20';
        $jsonResult['navigation']['st'] = '20';
        $jsonResult['navigation']['of'] = '0';

        if ($_GET['fr']) {
            $jsonResult['navigation']['fr'] = $_GET['fr'];
        }

        if ($_GET['st']) {
            $jsonResult['navigation']['st'] = $_GET['st'];
        }

        $jsonResult['navigation']['to'] = (int)$jsonResult['navigation']['fr'] + (int)$jsonResult['navigation']['st'];
        //$limit = (int)$jsonResult['navigation']['to'] - (int)$jsonResult['navigation']['st'];



        $sql = "SELECT * FROM usuarios"; // Reemplaza "tu_tabla" con el nombre de tu tabla
        $result = $conn->query($sql);
        if ($result) {
            $num_rows = $result->num_rows; // Esto te dará el número de filas del resultado
            //echo "El número de líneas es: " . $num_rows;
            $jsonResult['navigation']['of'] = $num_rows;
        }


        $sql = "SELECT * FROM usuarios LIMIT " . $limit = (int)$jsonResult['navigation']['st'] . " OFFSET " . $jsonResult['navigation']['fr'] . ";";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $jsonResult['content'][] = $row;
            }
        }
        break;

    case "green":
        echo "Your favorite color is green!";
        break;
    default:
        //echo "Your favorite color is neither red, blue, nor green!";
}
