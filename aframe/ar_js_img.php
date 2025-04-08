<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>AR.js NFT Demo</title>

    <script src="https://cdn.jsdelivr.net/gh/aframevr/aframe@1.6.0/dist/aframe-master.min.js"></script>
    <script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar-nft.js"></script>

    <style>
        .arjs-loader {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .arjs-loader div {
            text-align: center;
            font-size: 1.25em;
            color: white;
        }
    </style>
</head>

<body style="margin: 0px; overflow: hidden">
    <div class="arjs-loader">
        <div>Loading, please wait...</div>
    </div>

    <a-scene
        vr-mode-ui="enabled: false"
        renderer="logarithmicDepthBuffer: true; precision: medium"
        embedded
        arjs="trackingMethod: best; sourceType: webcam; debugUIEnabled: false">
        <a-nft
            type="nft"
            url="https://raw.githack.com/AR-js-org/AR.js/master/aframe/examples/image-tracking/nft/trex/trex-image/trex"
            smooth="true"
            smoothCount="10"
            smoothTolerance=".01"
            smoothThreshold="5">
            <a-entity
                gltf-model="https://raw.githack.com/AR-js-org/AR.js/master/aframe/examples/image-tracking/nft/trex/scene.gltf"
                scale="5 5 5"
                position="50 150 0"></a-entity>
        </a-nft>

        <a-entity camera></a-entity>
    </a-scene>
</body>

</html>