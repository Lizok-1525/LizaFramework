<?php
$type = $_GET['type'] ?? '';

switch ($type) {
    case 'box':
        echo '<a-box class="clickable-box" position="9 1 8" scale="1.5 1.5 1.5" color="tomato" depth="1" height="1" width="1" animation="property: rotation; to: 0 360 0; loop: true; dur: 1000;" animation="property: object3D.position.y; to: 4.2; dir: alternate; dur: 2000; loop: true"></a-box>';
        break;

    case 'sphere':
        echo '<a-sphere class="clickable-box" position="9 1 5" radius="1" color="skyblue" animation="property: object3D.position.y; to: 4.2; dir: alternate; dur: 2000; loop: true" ></a-sphere>';
        break;

    case 'model':
        echo '<a-entity class="clickable-box" gltf-model="#testbed" position="9 -1 2" rotation="0 -90 0" scale="2 2 2" move-on-key></a-entity>';
        break;

    default:
        echo '<a-text value="Elemento no válido" position="0 2 -5" color="red"></a-text>';
        break;
}
