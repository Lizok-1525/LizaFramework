<?php
$type = $_GET['type'] ?? '';

switch ($type) {
    case 'box':
        echo '<a-box class="clickable-box" position="0 1 -4" color="tomato" depth="1" height="1" width="1" animation="property: rotation; to: 0 360 0; loop: true; dur: 10000"></a-box>';
        break;

    case 'sphere':
        echo '<a-sphere class="clickable-box" position="0 2 4" radius="1" color="skyblue" animation="property: object3D.position.y; to: 2.2; dir: alternate; dur: 2000; loop: true" ></a-sphere>';
        break;

    case 'model':
        echo '<a-entity gltf-model="#testbed" position="0 0 -5" scale="0.5 0.5 0.5"></a-entity>';
        break;

    default:
        echo '<a-text value="Elemento no válido" position="0 2 -5" color="red"></a-text>';
        break;
}
