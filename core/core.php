<?php

class core
{
    function convertirTextoAHTML($texto)
    {
        // Escapar caracteres HTML peligrosos
        $texto = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');

        $texto = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $texto);
        $texto = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $texto);
        $texto = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $texto);
        $texto = preg_replace('/^#### (.+)$/m', '<h4>$1</h4>', $texto);
        $texto = preg_replace('/^##### (.+)$/m', '<h5>$1</h5>', $texto);
        $texto = preg_replace('/^###### (.+)$/m', '<h6>$1</h6>', $texto);

        // Convertir líneas vacías en párrafos
        $texto = preg_replace("/\n\s*\n/", "</p><p>", $texto);

        $texto = preg_replace('/!\[(.*?)\]\((.*?)\)/', '<img src="$2" alt="$1" style="max-width:100%;">', $texto);

        $texto = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $texto); // **negrita**
        $texto = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $texto);

        $texto = preg_replace('/(?:\n|^)- (.+)/', '<li>$1</li>', $texto);
        $texto = preg_replace('/(<li>.+<\/li>)/s', '<ul>$1</ul>', $texto);

        $texto = preg_replace('/\[(.*?)\]\((https?:\/\/[^\s]+)\)/', '<a href="$2" target="_blank" rel="nofollow">$1</a>', $texto);

        // Convertir saltos de línea en <br> dentro de párrafos
        $texto = preg_replace("/\n/", "<br>", $texto);

        // Envolver en <p> si no tiene etiquetas HTML ya
        return "<p>$texto</p>";
    }


    function obtenerArticuloHTML($id, $conn)
    {

        global $conn;

        // Preparar la consulta segura para evitar SQL Injection
        $stmt = $conn->prepare("SELECT titulo, contenido, fecha FROM noticias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verificar si el artículo existe
        if ($resultado->num_rows > 0) {
            $articulo = $resultado->fetch_assoc();

            $fecha = date("d/m/Y", strtotime($articulo['fecha']));

            $contenidoHTML = $this->convertirTextoAHTML($articulo['contenido']);

            // Generar el HTML del artículo
            $html = "<article>";
            $html .= "<h1>" . htmlspecialchars($articulo['titulo']) . "</h1>";
            $html .= "<p class='meta'><em>Publicado el $fecha </strong></em></p>";
            $html .= "<div class='contenido'>$contenidoHTML</div>";
            $html .= "</article>";

            return $html;
        } else {
            return "<p>Artículo no encontrado.</p>";
        }
    }


    function generarSlug($titulo)
    {
        // Convertir a minúsculas
        $titulo = mb_strtolower($titulo, 'UTF-8');

        // Reemplazar caracteres acentuados o especiales
        $caracteres = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ñ' => 'n',
            'ä' => 'a',
            'ë' => 'e',
            'ï' => 'i',
            'ö' => 'o',
            'ü' => 'u',
            'ß' => 'ss'
        ];
        $titulo = strtr($titulo, $caracteres);

        // Reemplazar cualquier carácter no alfanumérico por un espacio
        $titulo = preg_replace('/[^a-z0-9]+/u', '-', $titulo);

        // Eliminar guiones al inicio o final
        $titulo = trim($titulo, '-');

        return $titulo;
    }


    function print_arr($arr)
    {
        echo '<pre style="border:1px dotted rgb(255, 17, 0);margin:5px;padding:5px;background-color:#222;color:#fff;min-height:400px;">';
        print_r($arr);
        echo '</pre>';
    }
}
