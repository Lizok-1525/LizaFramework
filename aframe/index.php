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

    <!-- Estilos -->
    <style>
        #loadElements,
        #loadPage,
        #loadElement {
            position: absolute;
            top: 10px;
            left: 250px;
            padding: 10px;
            background-color: white;
            z-index: 999;
            cursor: pointer;
        }

        #htmlReturnButton,
        #loadNav {
            position: fixed;
            top: .5em auto;
            left: .5em auto;
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
    <button id="loadPage" style="position: absolute; top: 10px; left: 90px; padding: 10px; z-index: 10;">
        Cargar nuevo elemento
    </button>
    <button id="loadElements">Crear cajas</button>
    <button id="htmlReturnButton">Return</button>
    <button class="loadElement" data-type="box" style="position: absolute; top: 40px; z-index: 20;">Cargar Caja</button>
    <button class="loadElement" data-type="sphere" style="position: absolute; top: 60px; z-index: 20;">Cargar Esfera</button>
    <button class="loadElement" data-type="model" style="position: absolute; top: 80px; z-index: 20">Cargar Modelo</button>

    <button id="loadNav">Abrir navegación</button>


    <!-- Escena A-Frame -->
    <a-scene fog="black" physics="debug: true">


        <a-entity id="navContainer" position="0 2 -4" visible="false" animation__scale="property: scale; to: 1 1 1; dur: 1000"></a-entity>



        <!-- Assets -->
        <a-assets>
            <img id="boxTexture" src="https://i.imgur.com/mYmmbrp.jpg">
            <img id="skyTexture" src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/sechelt.jpg">
            <a-asset-item id="testbed"
                src="https://cdn.aframe.io/examples/post-processing/fancy-car.glb"></a-asset-item>
        </a-assets>

        <!-- Contenedor para elementos dinámicos -->
        <a-entity id="content" position="0 1 -4"></a-entity>
        <a-entity id="contenido" position="0 2 -8"></a-entity>

        <!-- Elementos estáticos de la escena -->
        <a-box src="#boxTexture" position="-3 2 -9" rotation="0 45 45" scale="1.5 1.5 1.5"
            animation__position="property: object3D.position.y; to: 2.2; dir: alternate; dur: 2000; loop: true"
            animation__mouseenter="property: scale; to: 2 2 2; dur: 300; startEvents: mouseenter"
            animation__mouseleave="property: scale; to: 2 2 2; dur: 300; startEvents: mouseleave"
            animation__click="property: rotation; from: 0 45 45; to: 0 405 45; dur: 1000; startEvents: click">
        </a-box>

        <a-sky src="#skyTexture"></a-sky>

        <!-- Sistema de cámara y controles -->
        <a-entity id="rig" position="0 0 2">
            <a-entity id="camera" position="0 1.6 0" camera look-controls wasd-controls></a-entity>
            <a-entity id="lhand" effect-controls="hand: left;"></a-entity>
            <a-entity id="rhand" effect-controls="hand: right;"></a-entity>
        </a-entity>

        <!-- Entorno -->
        <a-entity environment="preset: forest; dressingAmount: 500"></a-entity>

        <!-- Cámara secundaria -->
        <a-camera>
            <a-cursor color="#FAFAFA"></a-cursor>
        </a-camera>
        <a-entity camera look-controls position="0 2 5"></a-entity>
    </a-scene>

    <!-- Scripts -->
    <script>
        window.addEventListener('load', () => {
            const scene = document.querySelector('a-scene');
            const params = new URLSearchParams(window.location.search);

            // Solo si viene con ?autoVR=true
            if (params.get('autoVR') !== 'true') return;

            scene.addEventListener('loaded', () => {
                if (scene.xrSession) return;
                if (scene.renderer.xr) {
                    scene.renderer.xr.enabled = true;
                    scene.renderer.xr.setSessionInit({
                        optionalFeatures: ['local-floor', 'bounded-floor']
                    });

                    navigator.xr.requestSession('immersive-vr', {
                        optionalFeatures: ['local-floor', 'bounded-floor']
                    }).then(session => {
                        scene.renderer.xr.setSession(session);
                    }).catch(err => {
                        console.warn('No se pudo iniciar VR automáticamente:', err);
                    });
                }
            });
        });



        // Componentes de A-Frame
        AFRAME.registerComponent('scale-on-mouseenter', {
            schema: {
                to: {
                    default: '2.5 2.5 2.5',
                    type: 'vec3'
                }
            },
            init: function() {
                var data = this.data;
                var el = this.el;
                this.el.addEventListener('mouseenter', function() {
                    el.object3D.scale.copy(data.to);
                });
            }
        });

        AFRAME.registerComponent('car-controls', {
            schema: {},
            init: function() {
                this.direction = new THREE.Vector3();
                this.speed = 0.05;
                window.addEventListener('keydown', (e) => {
                    const car = this.el.object3D.position;
                    switch (e.key) {
                        case 'ArrowUp':
                        case 'w':
                            car.z -= this.speed;
                            break;
                        case 'ArrowDown':
                        case 's':
                            car.z += this.speed;
                            break;
                        case 'ArrowLeft':
                        case 'a':
                            car.x -= this.speed;
                            break;
                        case 'ArrowRight':
                        case 'd':
                            car.x += this.speed;
                            break;
                    }
                });
            }
        });

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

        AFRAME.registerComponent('show-on-distance', {
            schema: {
                maxDistance: {
                    type: 'number',
                    default: 5
                }
            },
            init: function() {
                this.camera = document.querySelector('[camera]');
            },
            tick: function() {
                const objPosition = this.el.object3D.position;
                const camPosition = this.camera.object3D.position;
                const distance = objPosition.distanceTo(camPosition);
                this.el.setAttribute('visible', distance < this.data.maxDistance);
            }
        });

        // Variables globales
        let count = 0; // contador para posicionar las cajas

        // Funciones auxiliares
        function getRandomColor() {
            const colors = ['tomato', 'orange', 'gold', 'deepskyblue', 'limegreen', 'violet'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', () => {
            // Configurar controles del coche
            const car = document.querySelector('#car');
            if (car) {
                car.setAttribute('car-controls', '');
            }

            // Configurar botón de retorno
            document.getElementById('htmlReturnButton').addEventListener('click', () => {
                window.location.href = './tuneles.php';
            });

            // Manejar visibilidad del botón en VR
            const scene = document.querySelector('a-scene');
            if (scene) {
                scene.addEventListener('enter-vr', () => {
                    document.getElementById('htmlReturnButton').style.display = 'none';
                });
                scene.addEventListener('exit-vr', () => {
                    document.getElementById('htmlReturnButton').style.display = 'block';
                });
            }

            // Configurar botón de carga de elementos
            /* $('#loadPage').click(function(e) {
                 e.preventDefault();
                 $("#contenido").load("../aframe/element.php");
             });*/

            $('.loadElement').click(function() {
                const type = $(this).data('type');

                $.ajax({
                    url: 'element.php',
                    type: 'GET',
                    data: {
                        type: type
                    },
                    success: function(data) {
                        $('#content').append(data);
                    },
                    error: function() {
                        alert('No se pudo cargar el elemento');
                    }
                });
            });


            $('#loadNav').click(function() {
                // Activa la animación de unfold y hace visible el contenedor
                $('#navContainer').attr('visible', 'true').get(0).emit('unfold');
            });

            // Configurar botón de creación de cajas
            $('#loadElements').click(function() {
                const x = count * 1.5; // separa cada caja 1.5 unidades en X
                const $box = $('<a-box>')
                    .attr('position', `${x} 1 -4`)
                    .attr('color', getRandomColor())
                    .attr('class', 'clickable-box')

                    .attr('show-on-distance', 'maxDistance: 6');
                $('#content').append($box);
                count++;
            });

            // Eliminar cajas al hacer clic
            $(document).on('click', '.clickable-box', function() {
                $(this).remove();
            });
        });
    </script>
</body>

</html>