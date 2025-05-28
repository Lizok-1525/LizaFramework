<div class="container content intro noticia">
    <div class="row">
        <div class="col">
            <?php


            if ($result && $result->num_rows > 0) { // Añadido $result && para seguridad
                $row = $result->fetch_assoc();
                echo $core->obtenerArticuloHTML($id, $conn); ?>
            <?php // print_r($row);  
            } ?>

        </div>
    </div>
</div>
<?php include(BASE_PATH . "/template/standard/navegacion.inc.php"); ?>