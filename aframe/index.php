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


    <button id="htmlReturnButton">Return</button>
    <button id="loadElement" style="left: 90px;">Crear cajas</button>
    <button class="loadElements" data-type="box" style="top: 50px;">Cargar Caja</button>
    <button class="loadElements" data-type="sphere" style="top: 50px;left: 270px;">Cargar Esfera</button>
    <button class="loadElements" data-type="model" style="top: 50px;left: 130px;">Cargar Modelo</button>


    <!-- Escena A-Frame -->
    <a-scene fog="black" physics="debug: true">


        <a-entity id="rig" position="0 0 0" wasd-controls>
            <a-entity camera look-controls="pointerLockEnabled: true" position="0 1.6 0">
                <a-cursor color="#FAFAFA"></a-cursor>
            </a-entity>
        </a-entity>


        <a-plane width="2" height="1" position="-7 1 -5" rotation="0 45 0"
            material="color: #FFF; opacity: 0.5; transparent: true"
            link-click="crear"
            text="value: Crear caja; align: center; color: white; width: 3">
        </a-plane>
        <a-plane width="2" height="1" position="-7 2 -5" rotation="0 45 0"
            material="color: #FFF; opacity: 0.5; transparent: true"
            cargar-elemento="box"
            text="value: Cargar caja; align: center; color: white; width: 3">
        </a-plane>
        <a-plane width="2" height="1" position="-8 2 -3" rotation="0 45 0"
            material="color: #FFF; opacity: 0.5; transparent: true"
            cargar-elemento="model"
            text="value: Cargar modelo; align: center; color: white; width: 3">
        </a-plane>
        <a-plane width="2" height="1" position="-8 1 -3" rotation="0 45 0"
            material="color: #FFF; opacity: 0.5; transparent: true"
            cargar-elemento="sphere"
            text="value: Cargar esfera; align: center; color: white; width: 3">
        </a-plane>



        <!-- Assets -->
        <a-assets>
            <img id="boxTexture" src="https://i.imgur.com/mYmmbrp.jpg">
            <img id="skyTexture" src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/sechelt.jpg">
            <a-asset-item id="testbed"
                src="https://cdn.aframe.io/examples/post-processing/fancy-car.glb"></a-asset-item>
        </a-assets>

        <!-- Contenedor para elementos dinámicos -->
        <a-entity id="content" position="0 1 -4"></a-entity>
        <a-entity id="contenido" position="9 1 8"></a-entity>

        <!-- Elementos estáticos de la escena -->
        <a-box src="#boxTexture" position="-3 2 -9" rotation="0 45 45" scale="1.5 1.5 1.5"
            animation__position="property: object3D.position.y; to: 2.2; dir: alternate; dur: 2000; loop: true"
            animation__mouseenter="property: scale; to: 2 2 2; dur: 300; startEvents: mouseenter"
            animation__mouseleave="property: scale; to: 2 2 2; dur: 300; startEvents: mouseleave"
            animation__click="property: rotation; from: 0 45 45; to: 0 405 45; dur: 1000; startEvents: click">
        </a-box>

        <a-sky src="#skyTexture"></a-sky>

        <!-- Sistema de cámara y controles -->

        <a-entity id="lhand" effect-controls="hand: left;"></a-entity>
        <a-entity id="rhand" effect-controls="hand: right;"></a-entity>


        <!-- Entorno -->
        <a-entity environment="preset: forest; dressingAmount: 500"></a-entity>


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
        AFRAME.registerComponent('link-click', {
            init: function() {
                this.el.addEventListener('click', () => {
                    const x = count * 1.5;
                    const $box = $('<a-box>')
                        .attr('position', `${x} 1 -1`)
                        .attr('rotation', '15 45 30')
                        .attr('color', getRandomColor())
                        .attr('class', 'clickable-box')
                        .attr('show-on-distance', 'maxDistance: 6');

                    $('#content').append($box);
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

            $('.loadElements').click(function() {
                const type = $(this).data('type');

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



            // Configurar botón de creación de cajas
            $('#loadElement').click(function() {
                const x = count * 1.5; // separa cada caja 1.5 unidades en X
                const $box = $('<a-box>')
                    .attr('position', `${x} 1 -1`)
                    .attr('rotation', '15 45 30')
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