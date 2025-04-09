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


        <a-camera id="camera" gps-camera rotation-reader></a-camera>
    </a-scene>
    <div id="result" style="position: fixed;"></div>
    <script>
        // Coordenadas GPS de referencia para el modelo
        const MODEL_LAT = 39.5731819;
        const MODEL_LON = 2.6593544;

        // Factor de escala para la conversión de grados a metros (aproximado)
        const LAT_TO_METERS = 111132; // Aproximadamente metros por grado de latitud
        const LON_TO_METERS_AT_MID_LAT = Math.cos(MODEL_LAT * Math.PI / 180) * 111386; // Aproximadamente metros por grado de longitud (depende de la latitud)



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


            let deltaLat = userLat - MODEL_LAT;
            let deltaLon = userLon - MODEL_LON;

            // Convertir las diferencias a metros (aproximado)
            let x = deltaLon * LON_TO_METERS_AT_MID_LAT; // Eje X (Este/Oeste)
            let z = -deltaLat * LAT_TO_METERS; // Eje Z (Norte/Sur) - Negativo porque Z positivo suele ser hacia adelante

            // Posicionar el modelo
            let model = document.querySelector('#model');
            model.setAttribute('position', `${x} 1 ${z}`); // Y se mantiene en 1 para estar a una altura razonable
            model.setAttribute('visible', 'true');

            document.getElementById('result').innerHTML += `<br> Posición modelo (relativa): X=${x.toFixed(2)}, Z=${z.toFixed(2)}`;

            // Función para actualizar la visibilidad del modelo
            /* function verificarProximidad() {
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

                document.getElementById('result').innerHTML = `modelo: ${modelX}, ${modelY}, ${modelZ} <br> camara: ${camX}, ${camY}, ${camZ}`;

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
*/
            // Verificar la proximidad cada 500ms
            setInterval(verificarProximidad, 500);
        }, function(error) {
            console.error('Error al obtener la ubicación GPS:', error);
            document.getElementById('result').innerHTML = 'Error al obtener la ubicación GPS.';
        });
    </script>
</body>

</html>