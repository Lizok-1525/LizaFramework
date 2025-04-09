<!DOCTYPE html>
<html>

<head>
    <script src="https://aframe.io/releases/1.2.0/aframe.min.js"></script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }

        #cameraFeed {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>
</head>

<body>
    <video id="cameraFeed" autoplay playsinline></video>
    <a-scene style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
        <a-entity id="model" gltf-model="./assets/scene.gltf" rotation="0 180 0" visible="false" position="0 1 -5"></a-entity>
        <a-camera id="camera" position="0 1.6 0"></a-camera>
    </a-scene>
    <div id="result" style="position: fixed; top: 10px; width: 100%; text-align: center; color: white;">Cargando ubicación...</div>

    <script>
        const MODEL_LAT = 39.5731819;
        const MODEL_LON = 2.6593544;
        const PROXIMITY_THRESHOLD_METERS = 10; // Umbral en metros (aproximado en 3D)
        const LAT_TO_METERS = 111132;
        const LON_TO_METERS_AT_MID_LAT = Math.cos(MODEL_LAT * Math.PI / 180) * 111386;

        const cameraFeed = document.getElementById('cameraFeed');
        const model = document.getElementById('model');
        const resultDiv = document.getElementById('result');
        const cameraElement = document.getElementById('camera');

        // Obtener acceso a la cámara
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment'
                    }
                })
                .then(function(stream) {
                    cameraFeed.srcObject = stream;
                })
                .catch(function(error) {
                    console.error('Error al acceder a la cámara:', error);
                    resultDiv.innerText = 'Error al acceder a la cámara.';
                });
        } else {
            resultDiv.innerText = 'La API getUserMedia no es compatible con este navegador.';
        }

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

                // La cámara en A-Frame está en (0, 1.6, 0) por defecto
                const cameraX = 0;
                const cameraY = 1.6;
                const cameraZ = 0;

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
                resultDiv.innerText = 'Error al obtener la ubicación.';
            }, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });
        }

        setInterval(updateLocation, 1000);
    </script>
</body>

</html>