<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A-Frame Scene with Dynamic Elements</title>
    <?php include("../template/standard/metas.inc.php"); ?>
    <!-- Scripts -->
    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
    <script src="https://unpkg.com/aframe-environment-component@1.3.x/dist/aframe-environment-component.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Estilos -->
    <style>
        #htmlReturnButton,
        .loadElements,
        #loadPage,
        #loadElement {
            position: fixed;
            top: .5em;
            left: .5em;
            color: white;
            box-shadow: 1px 1px 1px 1px #000000;
            background-color: transparent;
            border-radius: 5px;
            border: 2px solid white;
            font-family: Arial;
            font-size: 1em;
            font-weight: bold;
            text-shadow: 2px 2px 1px #000000;
            padding: 0.25em 0.5em;
            z-index: 20;
        }

        #htmlReturnButton:hover {
            color: lightgrey;
            border-color: lightgrey;
        }
    </style>
</head>

<body>
    <!-- Controles UI -->
    <button id="htmlReturnButton" link-on-click="tuneles.php">Return</button>
    <button id="loadElement" style="left: 90px;">Crear cajas</button>
    <button class="loadElements" style="top: 50px;" onclick="cargarModelo(3)">Cargar Pato</button>
    <button class="loadElements" onclick="cargarModelo(2)" style="top: 50px;left: 270px;">Cargar Arbol</button>
    <button class="loadElements" onclick="cargarModelo(1)" style="top: 50px; left: 130px;">Cargar Coche</button>
    <button class="loadElements" style="left: 210px;" onclick="cargarTodos()">Cargar Todos</button>


    <!-- Escena A-Frame -->
    <a-scene fog="black" physics="debug: true">

        <a-entity id="myCamera" camera wasd-controls look-controls="pointerLockEnabled: true" position="0 1.6 0">
            <a-cursor color="#FAFAFA" id="myCursor" raycaster="objects: .clickable, .clickable-box"></a-cursor>
        </a-entity>

        <a-plane class="clickable" width="2" height="1" position="-7 1 -5" rotation="0 45 0"
            material="color: #FFF; opacity: 0.5; transparent: true"
            link-click="crear"
            text="value: Crear caja; align: center; color: white; width: 3">
        </a-plane>
        <a-plane class="clickable" width="2" height="1" position="-7 2 -5" rotation="0 45 0"
            material="color: #FFF; opacity: 0.5; transparent: true"
            cargar-elemento="box"
            text="value: Cargar caja; align: center; color: white; width: 3">
        </a-plane>
        <a-plane class="clickable" width="2" height="1" position="-7 3 -5" rotation="0 45 0"
            material="color: #FFF; opacity: 0.5; transparent: true"
            cargar-elemento="sphere"
            text="value: Cargar esfera; align: center; color: white; width: 3">
        </a-plane>

        <!-- Assets -->
        <a-assets>
            <img id="boxTexture" src="https://i.imgur.com/mYmmbrp.jpg">
            <a-asset-item id="testbed"
                src="https://cdn.aframe.io/examples/post-processing/fancy-car.glb"></a-asset-item>
        </a-assets>

        <!-- Contenedor para elementos dinámicos -->
        <a-entity id="content" position="0 1 -4"></a-entity>
        <a-entity id="contenido" position="2 1 2"></a-entity>
        <a-entity id="model-container"></a-entity>

        <!-- Elementos estáticos de la escena -->
        <a-box src="#boxTexture" position="-3 2 -9" rotation="0 45 45" scale="1.5 1.5 1.5"
            animation__position="property: object3D.position.y; to: 2.2; dir: alternate; dur: 2000; loop: true"
            animation__mouseenter="property: scale; to: 2 2 2; dur: 300; startEvents: mouseenter"
            animation__mouseleave="property: scale; to: 2 2 2; dur: 300; startEvents: mouseleave"
            animation__click="property: rotation; from: 0 45 45; to: 0 405 45; dur: 1000; startEvents: click">
        </a-box>

        <!-- Entorno -->
        <a-entity environment="preset: forest; dressingAmount: 500"></a-entity>

    </a-scene>

    <!-- Scripts -->
    <script>
        AFRAME.registerComponent('move-on-key', {
            schema: {
                speed: {
                    type: 'number',
                    default: 0.1
                }
            },
            init: function() {
                this.position = this.el.object3D.position;
                this.keysPressed = {};

                // Escuchar teclas presionadas
                window.addEventListener('keydown', (e) => {
                    this.keysPressed[e.key.toLowerCase()] = true;
                });
                window.addEventListener('keyup', (e) => {
                    this.keysPressed[e.key.toLowerCase()] = false;
                });
            },
            tick: function() {
                const speed = this.data.speed;

                // Movimiento básico con WASD
                if (this.keysPressed['l']) this.position.z -= speed;
                if (this.keysPressed['o']) this.position.z += speed;
                if (this.keysPressed['ñ']) this.position.x -= speed;
                if (this.keysPressed['k']) this.position.x += speed;
                if (this.keysPressed['i']) this.position.y += speed;
                if (this.keysPressed['p']) this.position.y -= speed;
            }
        });

        function cargarModelo(id) {
            fetch('get-model.php?ID=' + id)
                .then(res => res.json())
                .then(data => {
                    const contenedor = document.getElementById('model-container');


                    const entidad = document.createElement('a-entity');
                    entidad.setAttribute('gltf-model', data.url);
                    entidad.setAttribute('position', data.posicion || '0 1.6 -3');
                    entidad.setAttribute('class', 'clickable-box');
                    entidad.addEventListener('click', function() {
                        contenedor.removeChild(this);
                    });
                    contenedor.appendChild(entidad);
                })
                .catch(err => console.error("Error cargando modelo:", err));
        }

        function cargarTodos() {
            fetch('get-modelos.php')
                .then(res => res.json())
                .then(data => {
                    const contenedor = document.getElementById('model-container');

                    let offsetX = -2; // separarlos horizontalmente

                    data.forEach(modelo => {
                        const entidad = document.createElement('a-entity');
                        entidad.setAttribute('gltf-model', modelo.url);
                        entidad.setAttribute('position', modelo.posicion || `${offsetX} 1.6 -3`);

                        entidad.addEventListener('click', function() {
                            contenedor.removeChild(this);
                        });

                        contenedor.appendChild(entidad);
                        offsetX += 2; // siguiente modelo más a la derecha
                    });
                })
                .catch(err => console.error("Error cargando modelos:", err));
        }

        AFRAME.registerComponent('link-on-click', {
            schema: {
                type: 'string'
            },
            init: function() {
                this.el.addEventListener('click', () => {
                    window.location.href = this.data;
                });
            }
        });

        // Componente para cargar cajas al hacer clic
        /* */
        AFRAME.registerComponent('link-click', {
            init: function() {
                this.el.addEventListener('click', () => {
                    const x = count * 1.5;
                    const box = document.createElement('a-box');
                    box.setAttribute('position', `${x} 1 -1`);
                    box.setAttribute('rotation', '15 45 30');
                    box.setAttribute('color', getRandomColor());
                    box.setAttribute('class', 'clickable');
                    box.setAttribute('show-on-distance', 'maxDistance: 6');

                    // Eliminar al hacer clic
                    box.addEventListener('click', function() {
                        this.parentNode.removeChild(this);
                    });

                    document.querySelector('#content').appendChild(box);
                    count++;
                });
            }
        });

        // Componente para cargar elementos dinámicamente
        AFRAME.registerComponent('cargar-elemento', {
            schema: {
                type: 'string'
            },
            init: function() {
                this.el.addEventListener('click', () => {
                    const type = this.data;

                    $.ajax({
                        url: 'element.php',
                        type: 'GET',
                        data: {
                            type: type
                        },
                        success: function(data) {
                            $('#contenido').append(data);
                        },
                        error: function() {
                            alert('No se pudo cargar el elemento');
                        }
                    });
                });
            }
        });

        //-----------------------------------------------
        // Variables globales
        let count = 0; // contador para posicionar las cajas

        // Funciones auxiliares
        function getRandomColor() {
            const colors = ['tomato', 'orange', 'gold', 'deepskyblue', 'limegreen', 'violet'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', () => {
            // Eliminar cajas al hacer clic
            $(document).on('click', '.clickable-box', function() {
                $(this).remove();
            });
        });
    </script>
</body>

</html>