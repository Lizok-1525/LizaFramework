<a-entity position="0 3 -8" scale="1 1 1" animation__position="property: position; to: 0 5 -8; dur: 2000"
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
                        material="color: #FFF; opacity: 0; transparent: true" link-on-click="./new_test.php">
                    </a-plane>

                    <!-- Quinto panel -->
                    <a-box mixin="board unhinge"
                        src="https://stemkoski.github.io/A-Frame-Examples/images/earth-sphere.jpg"
                        rotation="-180 0 0" animation="delay: 750">
                        <a-plane width="6" height="1" position="0 0 0.03" rotation="0 0 0"
                            material="color: #FFF; opacity: 0; transparent: true"
                            link-on-click="./ar_js_new.php">
                        </a-plane>
                    </a-box>
                </a-box>
            </a-box>
        </a-box>
    </a-box>
</a-entity>

<!-- Elementos adicionales de la escena -->
<a-image position="0 -1 0" src="#shadow" rotation="-90 0 0" scale="6 6 6">
</a-image>

<a-light type="directional" color="#fff" intensity="0.628" position="-1 2 1">
</a-light>

<a-light type="ambient" color="#fff">
</a-light>

<!-- Cámara y controles -->
<a-camera>
    <a-cursor color="#000"></a-cursor>
</a-camera>

<a-entity id="leftHand" link-controls="hand: left">
</a-entity>

<a-entity id="rightHand" link-controls="hand: right">
</a-entity>