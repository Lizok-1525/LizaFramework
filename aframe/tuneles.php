<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Animation: Unfold - A-Frame">
    <title>Animation</title>

    <!-- Inclusión de metadatos y configuraciones comunes -->
    <?php include("../template/standard/metas.inc.php"); ?>

    <!-- Librerías A-Frame y componentes externos -->
    <script src="https://aframe.io/aframe/dist/aframe-master.min.js"></script>
    <script src="https://aframe.io/aframe/examples/showcase/link-traversal/js/components/aframe-tooltip-component.js"></script>
    <script src="https://aframe.io/aframe/examples/showcase/link-traversal/js/components/camera-position.js"></script>
    <script src="https://aframe.io/aframe/examples/showcase/link-traversal/js/components/link-controls.js"></script>
    <script src="https://unpkg.com/aframe-environment-component@1.5.0/dist/aframe-environment-component.min.js"></script>

    <!-- Componente personalizado para redireccionar al hacer clic -->
    <script>
        AFRAME.registerComponent('link-on-click', {
            schema: {
                type: 'string'
            },
            init: function() {
                this.el.addEventListener('click', () => {
                    this.el.sceneEl.exitVR(); // Salir de VR antes de cambiar de página
                    setTimeout(() => {
                        window.location.href = this.data + '?autoVR=true';
                        // Intentar volver a entrar en VR después de cargar la nueva página
                        setTimeout(() => {
                            const newScene = document.querySelector('a-scene');
                            if (newScene) newScene.enterVR();
                        }, 1000);
                    }, 100);
                });
            }
        });
    </script>
</head>

<body>
    <!-- Escena principal de A-Frame -->
    <a-scene background="color:rgb(185, 185, 185)">

        <!-- Carga de recursos y mixins reutilizables -->
        <a-assets>
            <img id="shadow" src="https://aframe.io/aframe/examples/assets/img/radial-shadow-2.png">

            <!-- Mixin para los paneles -->
            <a-mixin id="board"
                geometry="depth: .05; height: 2; width: 6"
                material="shader: flat"
                pivot="0 0.5 0"
                position="0 -1.9 0">
            </a-mixin>

            <!-- Mixin para animación de apertura -->
            <a-mixin id="unhinge"
                animation="property: rotation; to: 0 0 0; dur: 1000">
            </a-mixin>
        </a-assets>

        <!-- Imagen curva de fondo tipo UI decorativa -->
        <a-curvedimage
            height="9"
            position="10 0 0"
            radius="4.7"
            rotation="0 30 0"
            scale="1.2 1.2 1.2"
            src="https://rawgit.com/aframevr/aframe/master/examples/showcase/curved-mockups/ui-3.png"
            theta-length="180"
            shadow>
        </a-curvedimage>

        <!-- Contenedor principal de paneles animados -->
        <a-entity position="0 7 -8"
            scale="1 1 1"
            animation__position="property: position; to: 0 5 -8; dur: 2000"
            animation__rotation="property: rotation; from: 0 60 0; to: 0 30 0; dur: 2500">

            <!-- Panel 1 -->
            <a-box mixin="board unhinge"
                src="https://cdn.aframe.io/link-traversal/thumbs/forest.png"
                rotation="-20 0 0"
                animation="delay: 1000">

                <!-- Zona clicable -->
                <a-plane width="6" height="1"
                    position="0 0 0.03"
                    rotation="0 0 0"
                    material="color: #FFF; opacity: 0; transparent: true"
                    link-on-click="./index.php">
                </a-plane>

                <!-- Panel 2 (anidado dentro del anterior) -->
                <a-box mixin="board unhinge"
                    src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/city.jpg"
                    rotation="-175 0 0"
                    animation="delay: 250">

                    <a-plane width="6" height="1"
                        position="0 0 0.03"
                        rotation="0 0 0"
                        material="color: #FFF; opacity: 0; transparent: true"
                        link-on-click="./galeria.php">
                    </a-plane>

                    <!-- Panel 3 -->
                    <a-box mixin="board unhinge"
                        src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/sechelt.jpg"
                        rotation="-180 0 0"
                        animation="delay: 500">

                        <a-plane width="6" height="1"
                            position="0 0 0.03"
                            rotation="0 0 0"
                            material="color: #FFF; opacity: 0; transparent: true"
                            link-on-click="./test.php">
                        </a-plane>

                        <!-- Panel 4 -->
                        <a-box mixin="board unhinge"
                            src="https://stemkoski.github.io/A-Frame-Examples/images/hexagons.png"
                            rotation="-180 0 0"
                            animation="delay: 750">

                            <a-plane width="6" height="1"
                                position="0 0 0.03"
                                rotation="0 0 0"
                                material="color: #FFF; opacity: 0; transparent: true"
                                link-on-click="./piramide.php">
                            </a-plane>

                            <!-- Panel 5 -->
                            <a-box mixin="board unhinge"
                                src="https://stemkoski.github.io/A-Frame-Examples/images/earth-sphere.jpg"
                                rotation="-180 0 0"
                                animation="delay: 750">

                                <a-plane width="6" height="1"
                                    position="0 0 0.03"
                                    rotation="0 0 0"
                                    material="color: #FFF; opacity: 0; transparent: true"
                                    link-on-click="./prueba.php">
                                </a-plane>

                            </a-box>
                        </a-box>
                    </a-box>
                </a-box>
            </a-box>
        </a-entity>

        <!-- Sombra decorativa bajo los paneles -->
        <a-image position="0 -5 0"
            src="#shadow"
            rotation="-90 0 0"
            scale="6 6 6">
        </a-image>

        <!-- Cámara con controles de mirada y cursor -->
        <a-entity camera look-controls position="0 -3 0">
            <a-camera>
                <a-cursor color="#000"></a-cursor>
            </a-camera>
        </a-entity>

        <!-- Controladores para manos (VR) -->
        <a-entity id="leftHand" link-controls="hand: left"></a-entity>
        <a-entity id="rightHand" link-controls="hand: right"></a-entity>
    </a-scene>
</body>

</html>