<?php
$type = $_GET['type'] ?? '';

switch ($type) {
    case 'level_1':
        echo '   <!-- Caja -->     <a-box position="0 1 -11" width="4" height="2" depth="0.2" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
            ammo-shape="type: box;"></a-box>
        <a-box position="-2 1 -9" width="0.2" height="2" depth="4" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
            ammo-shape="type: box;"></a-box>
        <a-box position="2 1 -9" width="0.2" height="2" depth="4" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
            ammo-shape="type: box;"></a-box>
        <a-box position="0 0.5 -7" width="4" height="1.5" depth="0.2" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;" ammo-shape="type: box;"></a-box>

        <!-- Botón para añadir piezas-->
        <a-box id="boton" position="0 0.2 -2" depth="0.2" height="0.2" width="1" color="#F00"
            class="spawn-rings clickable" shadow
            ammo-body="type: static;"
            ammo-shape="type: box;">
        </a-box>  


  ';
        break;

    case 'level_2':
        echo '
      <a-box position="0 0 -8" width="3" height="0.2" depth="3" color="#CCC" ammo-body="type: static;" ammo-shape="type: box;"></a-box>

        <a-box id="boton-bloque" position="0 0.5 -2" depth="0.2" height="0.2" width="1.5" color="#00F"
            class="clickable" shadow
            ammo-body="type: static;"
            ammo-shape="type: box;">
           
        </a-box>
    ';
        break;

    case 'level_3':
        echo '<!-- Base para construir -->
    <a-box position="0 0 -8" width="6" height="0.2" depth="3" color="#888" ammo-body="type: static;" ammo-shape="type: box;"></a-box>

    <!-- Botón para generar piezas -->
    <a-box id="boton-figura" position="0 0.5 -2" width="1.5" height="0.3" depth="0.3" color="#0F0"
        class="clickable" shadow
        ammo-body="type: static;" ammo-shape="type: box;"></a-box>
    ';
        break;

    default:
        echo '<a-text value="Has jugado a los 3 niveles ya" position="0 2 -5" color="red"></a-text>';
        break;
}
