<?php //print_r($usuarios) 
?>
<style>
    /* .page-item.active .page-link {
        background-color: #ff6600;
      
        border-color: #ff6600;
     
        color: white;
        
    }*/

    .page-link.active {
        background-color: rgb(243, 255, 190);
        /* Cambia este color al que quieras */
        border-color: #ff6600;
        /* Opcional, para que el borde combine */
        color: white;
    }
</style>
<div class="row intro ">
    <h1> Testeo</h1>
    <?php if ($usuarios['content']) { ?>
        <ul>

            <?php

            foreach ($usuarios['content'] as $usuario) {

            ?>
                <li>
                    <?php
                    echo $usuario['ID'] . " - " . $usuario['name'] . " - " . $usuario['email'];
                    ?>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>

        <p>Error al obtener los datos.</p>
    <?php } ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center ">
            <li class="page-item">
                <a class="page-link link-secondary" href="?pagina=1">
                    << Primera</a>
            </li>
            <li class="page-item"><?php if ($pagina_actual > 1) { ?><a class="page-link link-secondary" href="?pagina=<?php echo $pagina_actual - 1; ?>">⬅ Anterior</a></li><?php } ?>
        <?php
        for ($i = $inicio_botones; $i <= $fin_botones; $i++) {
            $active_class = ($i == $pagina_actual) ? 'active' : '';
            echo '<li class="page-item ' . $active_class . '"><a class="page-link link-secondary" href="?pagina=' . $i . '">' . $i . '</a></li>';
        }
        ?>

        <li class="page-item"><?php if ($pagina_actual < $total_paginas) { ?><a class="page-link link-secondary" href="?pagina=<?php echo $pagina_actual + 1; ?>">Siguiente ➡</a></li><?php } ?>
    <li class="page-item">
        <a class="page-link link-secondary" href="?pagina=<?php echo $total_paginas; ?>">Última >></a>
    </li>
        </ul>
    </nav>
</div>