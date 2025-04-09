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

            top: 0;
            left: 0;
            width: 90%;
            height: 100%;
        }

        #result {
            background-color: blue;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: black;
            z-index: 1;
        }
    </style>
</head>

<body style='margin: 0; overflow: hidden;'>

    <a-scene
        vr-mode-ui="enabled: false"
        embedded
        arjs='sourceType: webcam; sourceWidth:1280; sourceHeight:960; displayWidth: 1280; displayHeight: 960; debugUIEnabled: false;'>
        <a-entity id="model" gltf-model="./assets/scene.gltf" rotation="0 180 0" scale="1 1 1" visible="false"></a-entity>
        <a-camera id="camera" gps-camera rotation-reader></a-camera>
    </a-scene>
    <div id="result">Cargando ubicación...</div>
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

        // Obtener la posición del usuario
        navigator.geolocation.getCurrentPosition(function(position) {
            let lat = position.coords.latitude;
            let lon = position.coords.longitude;

            document.getElementById('result').innerText = `Ubicación: ${lat}, ${lon}`;

            // Mapeo de coordenadas geográficas a A-Frame (escala ajustada)
            let x = lon * 100; // Ajusta esta escala si es necesario
            let z = lat * 100; // Ajusta esta escala si es necesario

            // Obtenemos el modelo y la cámara en la escena
            // let model = document.querySelector('#model');
            let camera = document.querySelector('#camera');

            // Asignamos la posición del modelo
            model.setAttribute('position', `${x} 1 ${-z}`);

            // Función para actualizar la visibilidad del modelo
            function verificarProximidad() {
                // Obtener las coordenadas de la cámara (usuario)
                let camPos = camera.getAttribute('position');
                let camX = camPos.x;
                let camY = camPos.y;
                let camZ = camPos.z;

                // Obtener las coordenadas del modelo
                let modelPos = model.getAttribute('position');
                let modelX = modelPos.x;
                let modelY = modelPos.y;
                let modelZ = modelPos.z;

                // Calcular la distancia entre el modelo y la cámara
                let distancia = calcularDistancia(camX, camY, camZ, modelX, modelY, modelZ);

                // Mostrar el modelo solo si la distancia es menor a 10 metros
                if (distancia <= 10) {
                    model.setAttribute('visible', 'true');
                } else {
                    model.setAttribute('visible', 'false');
                }

                console.log(`Distancia: ${distancia} metros`);
            }

            // Verificar la proximidad cada 500ms
            setInterval(verificarProximidad, 500);
        });
    </script>
</body>

</html>