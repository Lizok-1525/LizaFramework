<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
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
        body {
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        button#find-me {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
            /* Asegúrate de que sea alto */
            padding: 10px 20px;
            font-size: 16px;
        }

        p#status {
            position: absolute;
            top: 60px;
            left: 20px;
            z-index: 1000;
            color: black;
            font-size: 14px;
        }

        a#map-link {
            position: absolute;
            top: 80px;
            left: 20px;
            z-index: 1000;
            color: black;
            font-size: 14px;
        }

        a-scene {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body style='margin: 0; overflow: hidden;'>

    <button id="find-me">Show my location</button><br />
    <p id="status"></p>
    <a id="map-link" target="_blank"></a>


    <a-scene vr-mode-ui="enabled: false" embedded
        arjs='sourceType: webcam; sourceWidth:1280; sourceHeight:960; displayWidth: 1280; displayHeight: 960; debugUIEnabled: false;'
        renderer='antialias: true; alpha: true'>
        <a-entity gltf-model="./assets/magnemite/scene.gltf" rotation="0 180 0" scale="0.15 0.15 0.15"
            gps-entity-place="longitude: 2.6586873; latitude: 39.5729811;" animation-mixer />
        <a-camera gps-camera rotation-reader></a-camera>
    </a-scene>

    <script>
        function geoFindMe() {
            const status = document.querySelector("#status");
            const mapLink = document.querySelector("#map-link");

            mapLink.href = "";
            mapLink.textContent = "";

            function success(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                status.textContent = "";
                mapLink.href = `https://www.openstreetmap.org/#map=18/${latitude}/${longitude}`;
                mapLink.textContent = `Latitude: ${latitude} °, Longitude: ${longitude} °`;
            }

            function error() {
                status.textContent = "Unable to retrieve your location";
            }

            if (!navigator.geolocation) {
                status.textContent = "Geolocation is not supported by your browser";
            } else {
                status.textContent = "Locating…";
                navigator.geolocation.getCurrentPosition(success, error);
            }
        }

        document.querySelector("#find-me").addEventListener("click", geoFindMe);

        window.onload = function() {
            const button = document.getElementById('find-me');
            if (button) {
                button.style.display = 'none'; // Ocultar temporalmente
                setTimeout(() => {
                    button.style.display = 'block'; // Mostrar después de un breve retraso
                }, 10); // Ajusta el tiempo si es necesario
            }
        };
    </script>

</body>

</html>