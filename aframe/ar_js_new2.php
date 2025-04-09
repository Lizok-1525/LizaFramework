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
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }

        a-scene {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        #result {
            position: fixed;
            top: 10px;
            width: 100%;
            text-align: center;
            color: black;
            z-index: 1;
        }
    </style>
</head>

<body>
    <div id="result">Cargando ubicación...</div>
    <a-scene
        vr-mode-ui="enabled: false"
        embedded
        arjs='sourceType: webcam; sourceWidth:1280; sourceHeight:960; displayWidth: 1280; displayHeight: 960; debugUIEnabled: false;'>
        <a-entity id="model" gltf-model="./assets/scene.gltf" rotation="0 180 0" scale="1 1 1" visible="