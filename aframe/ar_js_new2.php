<!DOCTYPE html>
<html>

<head>
    <script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
</head>

<body>
    <a-scene>
        <a-entity id="model" gltf-model="#model3D" position="0 1 -5" rotation="0 180 0"></a-entity>

        <!-- Aquí puedes agregar un lugar para mostrar el modelo -->
        <a-assets>
            <a-asset-item id="model3D" src="./assets/scene.gltf"></a-asset-item>
        </a-assets>

        <a-camera position="0 1.6 0"></a-camera>
    </a-scene>

    <script>
        navigator.geolocation.getCurrentPosition(function(position) {
            let lat = position.coords.latitude;
            let lon = position.coords.longitude;

            // Asumiendo que la latitud y longitud deben ser transformadas en coordenadas de A-Frame
            // Necesitamos hacer un mapeo. Este es un ejemplo muy básico:

            let x = lon * 1000; // Mapea longitud (ajusta el factor según necesites)
            let z = lat * 1000; // Mapea latitud (ajusta el factor según necesites)

            // Obtenemos el modelo de la escena
            let model = document.querySelector('#model');

            // Actualizamos la posición del modelo
            model.setAttribute('position', `${x} 1 ${-z}`);

            console.log(`Posición del modelo ajustada a Lat: ${lat}, Lon: ${lon}`);
        });
    </script>
</body>

</html>