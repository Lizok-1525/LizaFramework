<?php

class routing
{
    public static function handleRequest()
    {

        global $conn;
        global $template_path;

        // Obtener la ruta desde el parámetro "route"
        $route = isset($_GET['route']) ? $_GET['route'] : 'index';

        // Dividir la ruta en partes
        $segments = explode('/', trim($route, '/'));

        // Determinar el controlador y la acción (si existen)
        $controller = !empty($segments[0]) ? $segments[0] : '';



        if ($segments[0] == "noticias") {
            if (isset($segments[1])) {
                $segments = explode('-', $segments[1]);
                $last_param = $segments[count($segments) - 1];
                $controller = "noticia";
            }
        }


        if ($segments[0] == "admin") {
            if (isset($segments[1])) {
                $segmentsExplode = explode('-', $segments[1]);
                $last_param = $segmentsExplode[count($segmentsExplode) - 1];
                $controller = "admin/" . $segmentsExplode[0];
            } elseif ($segments[1] == "cerrar-sesion") {
                session_start();
                session_destroy();
                header("Location: /");
                exit();
                $controller = "index";
            } else {
                $controller = "admin/index";
            }
        }


        $filename_model = "app/models/{$controller}.php";
        if (file_exists($filename_model)) {
            require_once($filename_model);
        } else {
            echo "The file $filename_model does not exist";
        }

        ob_start();
        $filename_view = "app/views/{$controller}.php";
        if (file_exists($filename_view)) {
            require_once($filename_view);
            $content = ob_get_clean();

            ob_start();
            include($template_path);
            $template = ob_get_clean();

            echo $template;
            // Return an array with content and variables
            return ['content' => $content, 'head_title' => isset($head_title) ? $head_title : '', 'head_description' => isset($head_description) ? $head_description : '', 'canonical_name' => isset($canonical_name) ? $canonical_name : ''];
        } else {
            header("HTTP/1.0 404 Not Found");
            require_once("app/views/errors/404.php");
            return ['content' => ob_get_clean(), 'head_title' => '', 'head_description' => ''];
        }
        //return ob_get_clean();
    }
}
