<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <title>GeoAR.js con Proximidad</title>
    <script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
    <script type='text/javascript' src='https://raw.githack.com/AR-js-org/AR.js/3.4.5/three.js/build/ar-threex-location-only.js'></script>
    <script type='text/javascript' src='https://raw.githack.com/AR-js-org/AR.js/3.4.5/aframe/build/aframe-ar.js'></script>
    <script>
        THREEx.ArToolkitContext.baseURL = 'https://raw.githack.com/jeromeetienne/ar.js/master/three.js/';
    </script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }

        #main-block {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 800px;
            /* Ajusta el ancho deseado */
            height: 600px;
            /* Ajusta la altura deseada */
            transform: translate(-50%, -50%);
            overflow: hidden;
        }

        #result {
            background-color: grey;
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            color: white;
            font-weight: bold;
            padding: 10px;
            z-index: 9999;
        }

        .aframebox {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <div id="main-block">
        <a-scene
            class="aframebox" embedded
            vr-mode-ui="enabled: false"
            arjs='sourceType: webcam; sourceWidth:1280; sourceHeight:960; debugUIEnabled: false;'>

            <a-entity
                id="model"
                gltf-model="./assets/scene.gltf"
                rotation="0 180 0"
                scale="5 5 5"
                visible="false">
            </a-entity>

            <a-camera gps-camera rotation-reader></a-camera>
        </a-scene>
    </div>
    <div id="result"> </div>
    <script>
        const model = document.querySelector('#model');
        const MODEL_LAT = 39.5709918;
        const MODEL_LON = 2.6660998;

        window.addEventListener('gps-camera-update-position', (e) => {
            const lat = e.detail.position.latitude;
            const lon = e.detail.position.longitude;

            document.getElementById('result').innerText = `Ubicación: ${lat}, ${lon}`;

            model.setAttribute('gps-entity-place', {
                latitude: MODEL_LAT,
                longitude: MODEL_LON
            });

            model.setAttribute('visible', 'true');
        });
    </script>
</body>

</html>