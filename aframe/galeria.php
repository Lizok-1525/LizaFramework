<html>

<head>
    <title>360° Image Browser</title>
    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
    <script src="https://unpkg.com/aframe-template-component@3.x.x/dist/aframe-template-component.min.js"></script>
    <script src="https://unpkg.com/aframe-layout-component@5.x.x/dist/aframe-layout-component.min.js"></script>
    <script src="https://unpkg.com/aframe-event-set-component@5.x.x/dist/aframe-event-set-component.min.js"></script>
    <script
        src="https://unpkg.com/aframe-proxy-event-component@2.1.0/dist/aframe-proxy-event-component.min.js"></script>

    <script
        src="https://unpkg.com/aframe-environment-component@1.3.x/dist/aframe-environment-component.min.js"></script>


</head>

<body>
    <script>
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
    </script>
    <a-scene>

        <a-assets>
            <!-- Images. -->
            <img id="city" src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/city.jpg">
            <img id="city-thumb" src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/thumb-city.jpg">
            <img id="cubes" src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/cubes.jpg">
            <img id="cubes-thumb" src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/thumb-cubes.jpg">
            <img id="sechelt" src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/sechelt.jpg">
            <img id="sechelt-thumb" src="https://cdn.aframe.io/360-image-gallery-boilerplate/img/thumb-sechelt.jpg">
            <img id="boxTexture" src="https://i.imgur.com/mYmmbrp.jpg">
        </a-assets>

        <!-- 360-degree image. -->
        <a-sky id="image-360" radius="10" src="#city"
            animation__fade="property: components.material.material.color; type: color; from: #FFF; to: #000; dur: 300; startEvents: fade"
            animation__fadeback="property: components.material.material.color; type: color; from: #000; to: #FFF; dur: 300; startEvents: animationcomplete__fade"></a-sky>

        <a-box material="color: white" position="0 2 6" rotation="0 45 45" scale="2 2 2"
            animation__position="property: object3D.position.y; to: 2.2; dir: alternate; dur: 2000; loop: true"
            animation__mouseenter="property: scale; to: 2.3 2.3 2.3; dur: 300; startEvents: mouseenter"
            animation__mouseleave="property: scale; to: 2 2 2; dur: 300; startEvents: mouseleave"
            animation__click="property: rotation; from: 0 45 45; to: 0 405 45; dur: 1000; startEvents: click"></a-box>

        <!-- <a-entity id="box" geometry="primitive: box" material="color: red"></a-entity>

         Link template we will build. -->
        <a-entity id="links" layout="type: line; margin: 1.5" position="-1.5 1 -4">
            <a-entity template="src: #link" data-thumb="#city-thumb" data-src="#city"></a-entity>
            <a-entity template="src: #link" data-thumb="#cubes-thumb" data-src="#cubes"></a-entity>
            <a-entity template="src: #link" data-thumb="#sechelt-thumb" data-src="#sechelt"></a-entity>

        </a-entity>
        <script id="link" type="text/html">
            <a-entity class="link"
                geometry="primitive: plane; height: 1; width: 1"
                material="shader: flat; src: ${thumb}"
                sound="on: click; src: #click-sound"
                event-set__mouseenter="scale: 1.2 1.2 1"
                event-set__mouseleave="scale: 1 1 1"
                event-set__click="_target: #image-360; _delay: 300; material.src: ${src}"
                proxy-event="event: click; to: #image-360; as: fade"></a-entity>
        </script>

        <!-- Camera + Cursor. -->
        <a-camera>
            <a-cursor id="cursor" color="#FAFAFA"
                animation__click="property: scale; from: 0.1 0.1 0.1; to: 1 1 1; easing: easeInCubic; dur: 150; startEvents: click"
                animation__clickreset="property: scale; to: 0.1 0.1 0.1; dur: 1; startEvents: animationcomplete__click"
                animation__fusing="property: scale; from: 1 1 1; to: 0.1 0.1 0.1; easing: easeInCubic; dur: 150; startEvents: fusing"></a-cursor>
        </a-camera>

    </a-scene>
    <script>
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

        // Ocultar botón HTML en VR
        document.querySelector('a-scene').addEventListener('enter-vr', function() {
            document.getElementById('htmlReturnButton').style.display = 'none';
        });
        document.querySelector('a-scene').addEventListener('exit-vr', function() {
            document.getElementById('htmlReturnButton').style.display = 'block';
        });
    </script>

    <style>
        #htmlReturnButton {
            position: fixed;
            top: .5em;
            left: .5em;
            cursor: pointer;
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
</body>

</html>