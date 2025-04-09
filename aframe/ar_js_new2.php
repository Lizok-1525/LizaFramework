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
            /* Colocar detrás de la escena de A-Frame */
        }
    </style>
</head>

<body>
    <video id="cameraFeed" autoplay playsinline></video>
    <a-scene style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
        <a-entity id="model" gltf-model="./assets/scene.gltf" rotation="0 180 0" visible="false" position="0 1 -5"></a-entity>
        <a-camera position="0 1.6 0"></a-camera>
    </a-scene>
    <div id="result" style="position: fixed; top: 10px; width: 100%; text-align: center; color: white;">Cargando ubicación...</div>

    <script>
        const MODEL_LAT = 39.5731819; // Latitud del modelo
        const MODEL_LON = 2.6593544; // Longitud del modelo
        const PROXIMITY_THRESHOLD = 10; // Distancia en metros para mostrar el modelo
        const cameraFeed = document.getElementById('cameraFeed');
        const model = document.getElementById('model');
        const resultDiv = document.getElementById('result');

        // Obtener acceso a la cámara
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment'
                    }
                }) // 'environment' para la cámara trasera
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

        function calcularDistanciaGPS(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // Radio de la Tierra en metros
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return R * c;
        }

        function updateLocation() {
            navigator.geolocation.getCurrentPosition(function(position) {
                let userLat = position.coords.latitude;
                let userLon = position.coords.longitude;

                resultDiv.innerText = `Latitud: ${userLat.toFixed(6)}, Longitud: ${userLon.toFixed(6)}`;

                const distance = calcularDistanciaGPS(userLat, userLon, MODEL_LAT, MODEL_LON);
                resultDiv.innerText += `<br> Distancia al modelo: ${distance.toFixed(2)} metros`;

                if (distance <= PROXIMITY_THRESHOLD) {
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

        // Actualizar la ubicación periódicamente (puedes ajustar el intervalo)
        setInterval(updateLocation, 1000);
    </script>
</body>

</html>