<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <title>GeoAR.js demo</title>
    <script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
    <script type='text/javascript'
        src='https://raw.githack.com/AR-js-org/AR.js/3.4.5/three.js/build/ar-threex-location-only.js'></script>
    <script type='text/javascript'
        src='https://raw.githack.com/AR-js-org/AR.js/3.4.5/aframe/build/aframe-ar.js'></script>
    <script>
        THREEx.ArToolkitContext.baseURL = 'https://raw.githack.com/jeromeetienne/ar.js/master/three.js/'
    </script>
    <style>
        button#getLocationBtn {
            position: fixed;
            /* Prueba con fixed en lugar de absolute */
            top: 20px;
            left: 20px;
            z-index: 1000;
            padding: 10px 20px;
            font-size: 16px;
        }

        p#location {
            position: fixed;
            top: 60px;
            left: 20px;
            z-index: 1000;
            color: black;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        a-scene {
            position: fixed;
            /* Haz lo mismo con la escena A-Frame */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>



<body style='margin: 0; overflow: hidden;'>

    <button id="getLocationBtn">Show my location</button><br />
    <p id="location"></p>

    <a-scene
        vr-mode-ui="enabled: false"
        embedded
        arjs='sourceType: webcam; sourceWidth:1280; sourceHeight:960; displayWidth: 1280; displayHeight: 960; debugUIEnabled: false;'>
        <a-entity gltf-model="./assets/magnemite/scene.gltf" rotation="0 180 0" scale="0.15 0.15 0.15" gps-entity-place="latitud: 39.5709918; longitud: 2.6660998;" animation-mixer />
        <a-camera gps-camera rotation-reader></a-camera>
    </a-scene>


    <script>

    </script>
    <script>
        document.getElementById('getLocationBtn').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                document.getElementById('location').innerText = "La geolocalización no es soportada por este navegador.";
            }
        });

        function showPosition(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            document.getElementById('location').innerText = `Latitud: ${lat}, Longitud: ${lon}`;
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    document.getElementById('location').innerText = "Permiso denegado para acceder a la ubicación.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    document.getElementById('location').innerText = "La ubicación no está disponible.";
                    break;
                case error.TIMEOUT:
                    document.getElementById('location').innerText = "La solicitud para obtener la ubicación ha caducado.";
                    break;
                case error.UNKNOWN_ERROR:
                    document.getElementById('location').innerText = "Se ha producido un error desconocido.";
                    break;
            }
        }
    </script>
</body>

</html>