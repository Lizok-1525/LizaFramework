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

</head>

<body>

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

    <div id="result"> resultados </div>
    <script>
        // Verificar el estado del permiso de geolocalización al cargar la página
        navigator.permissions.query({
                name: 'geolocation'
            })
            .then(function(permissionStatus) {
                if (permissionStatus.state === 'granted') {
                    document.getElementById('result').innerText = 'El permiso de geolocalización está concedido.';

                    console.log('permiso concedido');

                    // Obtener y mostrar coordenadas
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lon = position.coords.longitude;
                            console.log('Coordenadas:', lat, lon);
                        },
                        function(error) {
                            console.error('Error al obtener la ubicación:', error);
                        }
                    );
                } else if (permissionStatus.state === 'denied') {
                    document.getElementById('result').innerText = 'El permiso de geolocalización está denegado.';
                } else {
                    document.getElementById('result').innerText = 'El permiso de geolocalización está pendiente.';
                }

                // Escuchar cambios en el estado del permiso
                permissionStatus.onchange = function() {
                    if (this.state === 'granted') {
                        document.getElementById('result').innerText = 'El permiso de geolocalización ha sido concedido.';

                        // Obtener y mostrar coordenadas al cambiar el estado a concedido
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const lat = position.coords.latitude;
                                const lon = position.coords.longitude;
                                console.log('Coordenadas:', lat, lon);

                                document.getElementById('result').innerHTML = 'Coordenadas Camara.' + lat + ' ' + lon;

                            },
                            function(error) {
                                console.error('Error al obtener la ubicación:', error);
                            }
                        );
                    } else if (this.state === 'denied') {
                        document.getElementById('result').innerText = 'El permiso de geolocalización ha sido denegado.';
                    } else {
                        document.getElementById('result').innerText = 'El permiso de geolocalización está pendiente.';
                    }
                };
            })
            .catch(function(error) {
                console.error('Error al verificar el permiso de geolocalización:', error);
                document.getElementById('result').innerText = 'No se pudo verificar el permiso de geolocalización.';
            });






        const model = document.querySelector('#model');
        const MODEL_LAT = 39.5709918;
        const MODEL_LON = 2.6660998;

        window.addEventListener('gps-camera-update-position', (e) => {
            const lat = e.detail.position.latitude;
            const lon = e.detail.position.longitude;


            console.log('lat:' + lat, 'lon:' + lon);

            document.getElementById('result').innerText = `Ubicación: ${lat}, ${lon}`;

            model.setAttribute('gps-entity-place', {
                latitude: MODEL_LAT,
                longitude: MODEL_LON
            });

            model.setAttribute('visible', 'true');
        });
    </script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }

        #main-block {}

        #result {
            background-color: #eee;
            width: 100%;
            text-align: center;
            color: #000;
            font-weight: bold;
            padding: 10px;
            z-index: 9999;
        }

        .aframebox {
            width: 100%;
            height: 80%;
        }

        #arjs-video {
            height: 80% !important;
        }
    </style>
</body>

</html>