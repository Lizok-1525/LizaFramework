<!DOCTYPE html>
<html>

<head>
    <script src="https://aframe.io/releases/1.2.0/aframe.min.js"></script>
</head>

<body>
    <a-scene style="position: fixed; top: 0; left: 0; width: 100%; height: 50%;">
        <!-- Modelo 3D que se muestra solo si está cerca -->
        <a-entity id="model" gltf-model="./assets/scene.gltf" rotation="0 180 0"></a-entity>

        <!-- Modelo 3D cargado -->


        <a-camera id="camera" position="0 1.6 0"></a-camera>
    </a-scene>
    <div id="result" style="position: fixed;"></div>
    <script>
        // Función para calcular la distancia entre dos puntos (en 3D)
        function calcularDistancia(x1, y1, z1, x2, y2, z2) {
            return Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2) + Math.pow(z2 - z1, 2));
        }

        // Obtener la posición del usuario
        navigator.geolocation.getCurrentPosition(function(position) {
            let lat = position.coords.latitude;
            let lon = position.coords.longitude;

            console.log(`Latitud: ${lat}, Longitud: ${lon}`);
            document.getElementById('result').innerHTML = `Latitud: ${lat}, Longitud: ${lon}`;

            // Mapeo de coordenadas geográficas a A-Frame (escala de 1000)
            let x = lon * 100;
            let z = lat * 100;

            // Obtenemos el modelo y la cámara en la escena
            let model = document.querySelector('#model');
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