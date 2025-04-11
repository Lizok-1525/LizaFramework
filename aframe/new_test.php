<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>AR con marcador Hiro</title>
    <script data-cfasync="false" src="https://aframe.io/releases/1.0.4/aframe.min.js"></script>
    <script data-cfasync="false"
        src="https://cdn.rawgit.com/jeromeetienne/ar.js/1.7.2/aframe/build/aframe-ar.min.js"></script>
</head>

<body style="margin: 0; overflow: hidden;">
    <a-scene embedded arjs>
        <a-entity id="returnButton" geometry="primitive: plane; width: 1; height: 0.5"
            material="color: #333; opacity: 0.8" position="-1.5 1.5 -2"
            text="value: Return; align: center; color: white; width: 5" class="clickable"
            link-on-click="./tuneles.php">
        </a-entity>

        <!-- También mantener el botón HTML para usuarios no-VR -->
        <button id="htmlReturnButton" onclick="window.location.href='./tuneles.php'">Return</button>

        <a-assets>
            <!-- Puedes reemplazar esta URL con la tuya -->
            <a-asset-item id="crowModel" src="../assets/images/gltf/Rise of the Guardianse.glb"></a-asset-item>
        </a-assets>

        <a-marker preset="hiro">
            <a-entity gltf-model="#crowModel" scale="0.5 0.5 0.5" position="0 0 0" rotation="0 0 0"
                animation="property: rotation; to: 0 360 0; loop: true; dur: 10000"></a-entity>
        </a-marker>

        <a-entity camera></a-entity>
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