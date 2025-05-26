<?php

class routing
{
    public static function handleRequest()
    {
        global $conn;
        global $template_path;

        // Obtener la ruta desde el parámetro "route", o 'index' si no se proporciona
        $route = isset($_GET['route']) ? $_GET['route'] : 'index';

        // Dividir la ruta en partes (por "/")
        $segments = explode('/', trim($route, '/'));

        // Determinar el controlador base desde el primer segmento
        $controller = !empty($segments[0]) ? $segments[0] : '';

        // Rutas especiales para "noticias"
        if ($segments[0] == "noticias") {
            if (isset($segments[1])) {
                $segments = explode('-', $segments[1]); // Separa por guión (ej: titulo-de-noticia-123)
                $last_param = $segments[count($segments) - 1]; // Último parámetro (id o slug)
                $controller = "noticia"; // Forzar controlador a "noticia"
            }
        }

        // Rutas especiales para "admin"
        if ($segments[0] == "admin") {
            if (isset($segments[1])) {
                $segmentsExplode = explode('-', $segments[1]);
                $last_param = $segmentsExplode[count($segmentsExplode) - 1]; // Último parámetro
                $controller = "admin/" . $segmentsExplode[0]; // Controlador tipo admin/usuarios, etc.
            } elseif ($segments[1] == "cerrar-sesion") {
                session_start();
                session_destroy(); // Cierra la sesión
                header("Location: /"); // Redirige a la raíz
                exit(); // Detiene la ejecución
                $controller = "index"; // Esto nunca se ejecuta (está después de exit)
            } else {
                $controller = "admin/index"; // Página principal del panel de admin
            }
        }

        // Cargar archivo de modelo si existe
        $filename_model = "app/models/{$controller}.php";
        if (file_exists($filename_model)) {
            require_once($filename_model);
        } else {
            echo "The file $filename_model does not exist";
        }

        // Captura el contenido de la vista
        ob_start();
        $filename_view = "app/views/{$controller}.php";
        if (file_exists($filename_view)) {
            require_once($filename_view);
            $content = ob_get_clean(); // Contenido renderizado de la vista

            // Cargar plantilla general
            ob_start();
            include($template_path);
            $template = ob_get_clean();

            // Mostrar plantilla completa
            echo $template;

            // Devolver array con contenido y metaetiquetas
            return [
                'content' => $content,
                'head_title' => isset($head_title) ? $head_title : '',
                'head_description' => isset($head_description) ? $head_description : '',
                'canonical_name' => isset($canonical_name) ? $canonical_name : ''
            ];
        } else {
            // Vista no encontrada: 404
            header("HTTP/1.0 404 Not Found");
            require_once("app/views/errors/404.php");
            return [
                'content' => ob_get_clean(),
                'head_title' => '',
                'head_description' => ''
            ];
        }

        // Código muerto (no se ejecuta): return ob_get_clean();
    }
}
