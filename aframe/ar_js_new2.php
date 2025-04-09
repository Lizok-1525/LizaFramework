<!DOCTYPE html>
<html>

<head>
    <title>AR.js A-Frame Location-based</title>
    <script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/AR-js-org/AR.js/aframe/build/aframe-ar-nft.js"></script>
</head>

<body style='margin: 0; overflow: hidden;'>
    <a-scene vr-mode-ui='enabled: false' embedded arjs='sourceType: webcam; videoTexture: true; debugUIEnabled: false' renderer='antialias: true; alpha: true'>
        <a-camera gps-new-camera='gpsMinDistance: 1'></a-camera>
        <a-box color="red" gps-entity-place="latitude: 39.573047; longitude: 2.659985" depth="10" height="10" width="10"></a-box>
        <a-box position="0 0 -5" color="blue" depth="2" height="2" width="2"></a-box>
    </a-scene>
    <script>
        window.addEventListener('gps-camera-update-position', e => {
            console.log('Posición actual:', e.detail.position);
        });
    </script>
</body>

</html>