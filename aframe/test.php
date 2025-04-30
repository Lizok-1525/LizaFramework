<!DOCTYPE html>
<html>

<head>
  <title>Pruebas</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://aframe.io/aframe/dist/aframe-master.min.js"></script>
  <script src="https://aframe.io/aframe/examples/showcase/model-viewer/background-gradient.js"></script>
  <script src="https://aframe.io/aframe/examples/showcase/model-viewer/animation-mixer.js"></script>
  <script src="https://aframe.io/aframe/examples/showcase/model-viewer/ar-shadows.js"></script>
  <script src="https://aframe.io/aframe/examples/showcase/model-viewer/model-viewer.js"></script>

  <style>
    .buttons {
      position: absolute;
      top: 20px;
      left: 20px;
      z-index: 999;
    }

    button {
      margin: 5px;
      padding: 10px 15px;
      font-size: 14px;
    }
  </style>
</head>

<body>
  <div class="buttons">
    <button onclick="changeModel('#buho', 'Buho')">Búho</button>
    <button onclick="changeModel('#aguila', 'Águila')">Aguila</button>
    <button onclick="changeModel('#halcón', 'Halcón')">Halcón</button>
    <button onclick="changeModel('#cuervo', 'Cuervo')">Cuervo</button>
  </div>



  <a-scene renderer="colorManagement: true;" model-viewer="gltfModel: #buho; title: The Birds" xr-mode-ui="XRMode: xr">
    <a-entity id="returnButton" geometry="primitive: plane; width: 1; height: 0.5" material="color: #333; opacity: 0.8"
      position="-1.5 1.5 -2" text="value: Return; align: center; color: white; width: 5" class="clickable"
      link-on-click="./tuneles.php">
    </a-entity>

    <!-- También mantener el botón HTML para usuarios no-VR -->
    <button id="htmlReturnButton" onclick="window.location.href='./tuneles.php'">Return</button>

    <a-assets timeout="10000">
      <a-asset-item id="buho" src="../assets/images/gltf/Owl.gltf" response-type="arraybuffer"
        title="Buho"></a-asset-item>
      <a-asset-item id="aguila" src="../assets/images/gltf/Eagle.gltf" response-type="arraybuffer"
        title="Aguila"></a-asset-item>
      <a-asset-item id="halcón" src="../assets/images/gltf/Hawk.gltf" response-type="arraybuffer"
        title="Halcon"></a-asset-item>
      <a-asset-item id="cuervo" src="../assets/images/gltf/Crow.gltf" response-type="arraybuffer"
        title="cuervo"></a-asset-item>
      <img id="shadow" src="shadow.png">

    </a-assets>
  </a-scene>
  <script>
    AFRAME.registerComponent('link-on-click', {
      schema: {
        type: 'string'
      },
      init: function() {
        this.el.addEventListener('click', () => {
          window.location.href = this.data;
        });
      }
    });

    // Ocultar botón HTML en VR
    document.querySelector('a-scene').addEventListener('enter-vr', function() {
      document.getElementById('htmlReturnButton').style.display = 'none';
    });
    document.querySelector('a-scene').addEventListener('exit-vr', function() {
      document.getElementById('htmlReturnButton').style.display = 'block';
    });
  </script>

  <style>
    #htmlReturnButton {
      position: fixed;
      top: .5em;
      left: .5em;
      cursor: pointer;
      color: white;
      box-shadow: 1px 1px 1px 1px #000000;
      background-color: transparent;
      border-radius: 5px;
      border: 2px solid white;
      font-family: Arial;
      font-size: 1em;
      font-weight: bold;
      text-shadow: 2px 2px 1px #000000;
      padding: 0.25em 0.5em;
      z-index: 20;
    }

    #htmlReturnButton:hover {
      color: lightgrey;
      border-color: lightgrey;
    }
  </style>
  <script>
    function changeModel(modelId, title) {
      const scene = document.querySelector('a-scene');
      scene.setAttribute('model-viewer', `gltfModel: ${modelId}; title: ${title}`);
    }
  </script>
</body>

</html>