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
        THREEx.ArToolkitContext.baseURL = 'https://raw.githack.com/jeromeetienne/ar.js/master/three.js/'
    </script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }

        a-scene {
            position: fixed;
            top: 0;
            left: 0;
            width: 90%;
            height: 100%;
        }

        #result {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: black;
            z-index: 1;
        }
    </style>
</head>

<body style='margin: 0; overflow: hidden;'>
    <div id="result">Cargando ubicación...</div>
    <a-scene
        vr-mode-ui="enabled: false"
        embedded
        arjs='sourceType: webcam; sourceWidth:1280; sourceHeight:960; displayWidth: 1280; displayHeight: 960; debugUIEnabled: false;'>
        <a-entity id="model" gltf-model="./assets/scene.gltf" rotation="0 180 0" scale="1 1 1" visible="false"></a-entity>
        <a-camera id="camera" gps-camera rotation-reader></a-camera>
    </a-scene>

    <script>
        const MODEL_LAT = 39.5709918;
        const MODEL_LON = 2.6660998;
        const PROXIMITY_THRESHOLD = 10; // Metros
        const LON_TO_METERS_AT_MID_LAT = 111320; // Aproximación para longitud
        const LAT_TO_METERS = 110574; // Aproximación para latitud



        const model = document.querySelector('#model');

        // Añadir el atributo gps-entity-place con las variables
        model.setAttribute('gps-entity-place', `latitude: ${MODEL_LAT}; longitude: ${MODEL_LON}`);

        function calcularDistancia(x1, y1, z1, x2, y2, z2) {
            return Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2) + Math.pow(z2 - z1, 2));
        }

        function updateLocation() {
            navigator.geolocation.getCurrentPosition(function(position) {
                let userLat = position.coords.latitude;
                let userLon = position.coords.longitude;
                let userAlt = position.coords.altitude || 0; // Obtener altitud si está disponible

                resultDiv.innerText = `Latitud: ${userLat.toFixed(6)}, Longitud: ${userLon.toFixed(6)}, Altitud: ${userAlt.toFixed(2)}`;

                // Convertir coordenadas geográficas a coordenadas relativas en metros (aproximado)
                const modelOffsetX = (MODEL_LON - userLon) * LON_TO_METERS_AT_MID_LAT;
                const modelOffsetZ = (MODEL_LAT - userLat) * LAT_TO_METERS;
                const modelOffsetY = 0; // Asumimos misma altitud para simplificar

                // Posición del modelo en la escena (puedes ajustar la altura inicial)
                const modelX = modelOffsetX;
                const modelY = 1;
                const modelZ = -modelOffsetZ - 5; // Ajuste en Z para que no esté justo en la cámara

                model.setAttribute('position', `${modelX} ${modelY} ${modelZ}`);

                // Calcular la distancia 3D entre la cámara y el modelo (en metros aproximados)
                const distance = calcularDistancia(cameraX, cameraY, cameraZ, modelX, modelY, modelZ);
                resultDiv.innerText += `<br> Distancia (3D aprox.): ${distance.toFixed(2)} metros`;

                if (distance <= PROXIMITY_THRESHOLD_METERS) {
                    model.setAttribute('visible', 'true');
                } else {
                    model.setAttribute('visible', 'false');
                }
            }, function(error) {
                console.error('Error al obtener la ubicación:', error);
                document.getElementById('result').innerText = 'Error al obtener la ubicación.';
            }, {
                enableHighAccuracy: true,
                timeout: Infinity,
                maximumAge: 0
            });
        }
    </script>
</body>

</html>