<!DOCTYPE html>
<html>

<head>
    <script src="https://aframe.io/releases/1.2.0/aframe.min.js"></script>
</head>

<body>
    <a-scene style="position: fixed; top: 0; left: 0; width: 100%; height: 50%;">
        <a-entity id="model" gltf-model="./assets/scene.gltf" rotation="0 180 0" visible="false"></a-entity>

        <a-camera id="camera" position="0 1.6 0"></a-camera>
    </a-scene>
    <div id="result" style="position: fixed;"></div>
    <script>
        // Coordenadas GPS de referencia para el modelo
        const MODEL_LAT = 39.5731819;
        const MODEL_LON = 2.6593544;

        // Factor de escala para la conversión de grados a metros (aproximado)
        const LAT_TO_METERS = 111132; // Aproximadamente metros por grado de latitud
        const LON_TO_METERS_AT_MID_LAT = Math.cos(MODEL_LAT * Math.PI / 180) * 111386; // Aproximadamente metros por grado de longitud (depende de la latitud)

        function calcularDistanciaGPS(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // Radio de la Tierra en metros
            const φ1 = lat1 * Math.PI / 180; // φ, λ en radianes
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return R * c; // Distancia en metros
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            let userLat = position.coords.latitude;
            let userLon = position.coords.longitude;

            console.log(`Latitud usuario: ${userLat}, Longitud usuario: ${userLon}`);
            document.getElementById('result').innerHTML = `Latitud usuario: ${userLat}, Longitud usuario: ${userLon}`;

            // Calcular la diferencia en latitud y longitud
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

            // Opcional: Actualizar la posición si la ubicación del usuario cambia
            // navigator.geolocation.watchPosition(function(newPosition) {
            //     let newUserLat = newPosition.coords.latitude;
            //     let newUserLon = newPosition.coords.longitude;

            //     let newDeltaLat = newUserLat - MODEL_LAT;
            //     let newDeltaLon = newUserLon - MODEL_LON;

            //     let newX = newDeltaLon * LON_TO_METERS_AT_MID_LAT;
            //     let newZ = -newDeltaLat * LAT_TO_METERS;

            //     model.setAttribute('position', `${newX} 1 ${newZ}`);
            //     document.getElementById('result').innerHTML = `Latitud usuario: ${newUserLat}, Longitud usuario: ${newUserLon} <br> Posición modelo (relativa): X=${newX.toFixed(2)}, Z=${newZ.toFixed(2)}`;
            // }, function(error) {
            //     console.error('Error al obtener la ubicación GPS:', error);
            // });

        }, function(error) {
            console.error('Error al obtener la ubicación GPS:', error);
            document.getElementById('result').innerHTML = 'Error al obtener la ubicación GPS.';
        });
    </script>
</body>

</html>