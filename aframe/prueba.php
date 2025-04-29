<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A-Frame Scene with Dynamic Elements</title>

    <!-- Scripts -->
    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
    <script
        src="https://unpkg.com/aframe-environment-component@1.3.x/dist/aframe-environment-component.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <!-- Controles UI -->

    <div id="model-list"></div>
    <button id="htmlReturnButton">Return</button>
    <button id="loadElement" style="left: 90px;">Crear cajas</button>
    <button class="loadElements" data-type="box" style="top: 50px;">Cargar Caja</button>
    <button class="loadElements" data-type="sphere" style="top: 50px;left: 270px;">Cargar Esfera</button>
    <button class="loadElements" data-type="model" style="top: 50px;left: 130px;"> Cargar coche</button>


    <!-- Escena A-Frame -->
    <a-scene fog="black" physics="debug: true">

        <!-- Assets -->
        <a-assets>
            <img id="boxTexture" src="https://i.imgur.com/mYmmbrp.jpg">
            <img id="skyTexture" src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/sechelt.jpg">

        </a-assets>

        <!-- Contenedor para elementos dinámicos -->
        <a-entity camera position="0 1.6 0">
            <a-cursor color="#FAFAFA"></a-cursor>
        </a-entity>
        <a-light type="ambient" intensity="1"></a-light>
        <a-entity id="model-container"></a-entity>

        <!-- Elementos estáticos de la escena -->
        <a-box src="#boxTexture" position="-3 2 -9" rotation="0 45 45" scale="1.5 1.5 1.5"
            animation__position="property: object3D.position.y; to: 2.2; dir: alternate; dur: 2000; loop: true"
            animation__mouseenter="property: scale; to: 2 2 2; dur: 300; startEvents: mouseenter"
            animation__mouseleave="property: scale; to: 2 2 2; dur: 300; startEvents: mouseleave"
            animation__click="property: rotation; from: 0 45 45; to: 0 405 45; dur: 1000; startEvents: click">
        </a-box>

        <a-sky src="#skyTexture"></a-sky>

        <!-- Entorno -->
        <a-entity environment="preset: forest; dressingAmount: 500"></a-entity>


    </a-scene>
    <script>
        fetch('get-model.php')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('model-list');
                const container = document.getElementById('model-container');

                data.forEach(model => {
                    const btn = document.createElement('button');
                    btn.textContent = model.nombre;
                    btn.onclick = () => {
                        container.innerHTML = '';
                        const entity = document.createElement('a-entity');
                        entity.setAttribute('gltf-model', model.modelo);
                        entity.setAttribute('position', model.posicion || '0 0 -3');
                        container.appendChild(entity);
                    };
                    list.appendChild(btn);
                });
            });
    </script>
</body>

</html>