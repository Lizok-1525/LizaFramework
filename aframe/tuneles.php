<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Animation: Unfold - A-Frame">
    <title>Animation</title>
    <?php include("../template/standard/metas.inc.php"); ?>
    <!-- Scripts -->
    <script src="https://aframe.io/aframe/dist/aframe-master.min.js"></script>
    <script
        src="https://aframe.io/aframe/examples/showcase/link-traversal/js/components/aframe-tooltip-component.js"></script>
    <script src="https://aframe.io/aframe/examples/showcase/link-traversal/js/components/camera-position.js"></script>
    <script src="https://aframe.io/aframe/examples/showcase/link-traversal/js/components/link-controls.js"></script>
    <script
        src="https://unpkg.com/aframe-environment-component@1.5.0/dist/aframe-environment-component.min.js"></script>

    <!-- Componentes personalizados -->
    <script>
        AFRAME.registerComponent('link-on-click', {
            schema: {
                type: 'string'
            },
            init: function() {
                this.el.addEventListener('click', () => {
                    this.el.sceneEl.exitVR(); // Salir de VR temporalmente
                    setTimeout(() => {
                        window.location.href = this.data + '?autoVR=true';

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
    <a-scene background="color:rgb(185, 185, 185)">
        <!-- Assets -->
        <a-assets>

            <img id="shadow" src="https://aframe.io/aframe/examples/assets/img/radial-shadow-2.png">

            <!-- Mixins -->
            <a-mixin id="board" geometry="depth: .05; height: 2; width: 6" material="shader: flat" pivot="0 0.5 0"
                position="0 -1.9 0">
            </a-mixin>
            <a-mixin id="unhinge" animation="property: rotation; to: 0 0 0; dur: 1000">
            </a-mixin>
        </a-assets>

        <a-curvedimage height="9" position="10 0 0" radius="4.7" rotation="0 30 0" scale="1.2 1.2 1.2" src="https://rawgit.com/aframevr/aframe/master/examples/showcase/curved-mockups/ui-3.png" theta-length="180" shadow></a-curvedimage>


        <!-- Escena principal -->
        <a-entity position="0 7 -8" scale="1 1 1" animation__position="property: position; to: 0 5 -8; dur: 2000"
            animation__rotation="property: rotation; from: 0 60 0; to: 0 30 0; dur: 2500">

            <!-- Primer panel -->
            <a-box mixin="board unhinge" src="https://cdn.aframe.io/link-traversal/thumbs/forest.png" rotation="-20 0 0"
                animation="delay: 1000">
                <a-plane width="6" height="1" position="0 0 0.03" rotation="0 0 0"
                    material="color: #FFF; opacity: 0; transparent: true" link-on-click="./index.php">
                </a-plane>

                <!-- Segundo panel -->
                <a-box mixin="board unhinge" src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/city.jpg"
                    rotation="-175 0 0" animation="delay: 250">
                    <a-plane width="6" height="1" position="0 0 0.03" rotation="0 0 0"
                        material="color: #FFF; opacity: 0; transparent: true" link-on-click="./galeria.php">
                    </a-plane>

                    <!-- Tercer panel -->
                    <a-box mixin="board unhinge"
                        src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/sechelt.jpg" rotation="-180 0 0"
                        animation="delay: 500">
                        <a-plane width="6" height="1" position="0 0 0.03" rotation="0 0 0"
                            material="color: #FFF; opacity: 0; transparent: true" link-on-click="./test.php">
                        </a-plane>

                        <!-- Cuarto panel -->
                        <a-box mixin="board unhinge"
                            src="https://stemkoski.github.io/A-Frame-Examples/images/hexagons.png" rotation="-180 0 0"
                            animation="delay: 750">
                            <a-plane width="6" height="1" position="0 0 0.03" rotation="0 0 0"
                                material="color: #FFF; opacity: 0; transparent: true" link-on-click="./piramide.php">
                            </a-plane>

                            <!-- Quinto panel -->
                            <a-box mixin="board unhinge"
                                src="https://stemkoski.github.io/A-Frame-Examples/images/earth-sphere.jpg"
                                rotation="-180 0 0" animation="delay: 750">
                                <a-plane width="6" height="1" position="0 0 0.03" rotation="0 0 0"
                                    material="color: #FFF; opacity: 0; transparent: true"
                                    link-on-click="./prueba.php">
                                </a-plane>
                            </a-box>
                        </a-box>
                    </a-box>
                </a-box>
            </a-box>
        </a-entity>

        <!-- Elementos adicionales de la escena -->
        <a-image position="0 -5 0" src="#shadow" rotation="-90 0 0" scale="6 6 6">
        </a-image>



        <!-- Cámara y controles -->
        <a-entity camera look-controls position="0 -3 0">
            <a-camera>
                <a-cursor color="#000"></a-cursor>
            </a-camera></a-entity>

        <a-entity id="leftHand" link-controls="hand: left">
        </a-entity>

        <a-entity id="rightHand" link-controls="hand: right">
        </a-entity>
    </a-scene>
</body>

</html>