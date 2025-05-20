<head>
  <meta charset="utf-8">
  <title>NAF con Firebase</title>
  <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
  <script src="https://unpkg.com/aframe-environment-component@1.5.0/dist/aframe-environment-component.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/MozillaReality/ammo.js@8bbc0ea/builds/ammo.wasm.js"></script>
  <script src="https://c-frame.github.io/aframe-physics-system/dist/aframe-physics-system.js"></script>
  <script src="https://c-frame.github.io/aframe-physics-system/examples/components/force-pushable.js"></script>
  <script src="https://c-frame.github.io/aframe-physics-system/examples/components/grab.js"></script>
  <script src="https://unpkg.com/networked-aframe@0.8.0/dist/networked-aframe.min.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-database-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-analytics-compat.js"></script>
  <script>
    const firebaseConfig = {
      apiKey: "AIzaSyBJ-N2uReWcU07zx7Fla61qCHyQElIuMB4",
      authDomain: "naf-firebase.firebaseapp.com",
      databaseURL: "https://naf-firebase-default-rtdb.asia-southeast1.firebasedatabase.app",
      projectId: "naf-firebase",
      storageBucket: "naf-firebase.firebasestorage.app",
      messagingSenderId: "303321259755",
      appId: "1:303321259755:web:2c41c5700a087421dee8ae",
      measurementId: "G-61P6R8Q44Y"
    };
    firebase.initializeApp(firebaseConfig);
    firebase.analytics();
  </script>

  <!-- Luego, cargar el adaptador de Firebase para networked-aframe -->
  <script src="https://unpkg.com/networked-aframe/dist/adapters/firebase-adapter.js"></script>
  <script>
    // Aquí, FirebaseAdapter ya está definido
    NAF.adapters.register('firebase', firebaseConfig);
  </script>

  <!-- Finalmente, cargar networked-aframe -->
  <script src="https://unpkg.com/networked-aframe@0.8.0/dist/networked-aframe.min.js"></script>


</head>

<body>

  <a-scene networked-scene="
    room: nombre-de-tu-sala;
    debug: true;
    connectOnLoad: true;
    adapter: firebase;
    firebase: {
      databaseURL: 'https://naf-firebase-default-rtdb.asia-southeast1.firebasedatabase.app/
'
    }" mouse-grab physics="driver: ammo; gravity: -10; debug: false" rotacion-crane stick-checker>

    <a-assets>
      <template id="avatar-template">
        <a-box color="blue" height="1.6" width="0.5" depth="0.5" networked="networkId: avatar"></a-box>
      </template>

      <template id="donut-template">
        <a-torus
          class="donut grabbable aro"
          ammo-body="type: dynamic; restitution: 0.7; mass: 1"
          ammo-shape="type: hull"
          networked="networkId: donut; owner: #mainCamera"
          rotation="90 0 0" radius="0.3" radius-tubular="0.05" segments-radial="8" segments-tubular="12">
        </a-torus>
      </template>
    </a-assets>



    <a-box position="0 4.5 -50"
      width="100"
      height="10"
      depth="1"
      color="yellow"
      ammo-body="type: static"
      ammo-shape="type: box">
    </a-box>

    <!-- Pared trasera (norte) -->
    <a-box position="0 4.5 50"
      width="100"
      height="10"
      depth="1"
      color="yellow"
      ammo-body="type: static"
      ammo-shape="type: box">
    </a-box>

    <!-- Pared izquierda (oeste) -->
    <a-box position="-50 4.5 0"
      width="1"
      height="10"
      depth="100"
      color="yellow"
      ammo-body="type: static"
      ammo-shape="type: box">
    </a-box>

    <!-- Pared derecha (este) -->
    <a-box position="50 4.5 0"
      width="1"
      height="10"
      depth="100"
      color="yellow"
      ammo-body="type: static"
      ammo-shape="type: box">
    </a-box>


    <!-- Botón para añadir piezas-->
    <a-box id="boton" position="2 2 -2" depth="1" height="0.2" width="0.2" color="#F00"
      class="clickable" spawn-on-click shadow
      ammo-body="type: static;"
      ammo-shape="type: box;">
    </a-box>

    <a-entity line="start: 50 1 40; end: -50 1 40; color: red"
      line__2="start: 50 1 -40; end: -50 1 -40; color: red"></a-entity>


    <a-box id="movingBox" networked="template:#avatar-template" material=" opacity: 0.7; transparent: true" position="0 0 -1" rotation="0 0 0" depth="1.8" height="2" width="2"
      ammo-body="type: kinematic;" ammo-shape="type: box;" wasd-controls>
      <a-box id="craneArm" networked="template:#avatar-template" material=" opacity: 0.7; transparent: true" position="0 1.5 -1" rotation="-30 0 0" depth="0.6" height="1.5" width="0.7"
        ammo-body="type: kinematic;" ammo-shape="type: box;" rotacion-crane></a-box>
      <a-entity camera id="mainCamera" networked="template:#avatar-template;" position="0 1.6 0.8" cursor="rayOrigin: mouse"
        raycaster="objects: .grabbable, .clickable"></a-entity>
    </a-box>


    <a-box position="0 -1 0" width="100" depth="100" height="1" color="yellow" material=" opacity: 0.7; transparent: true" visible="false" ammo-body="type: static; restitution: 0.8" ammo-shape="type: box"> </a-box>

    <a-entity environment position="0 -0.5 0"></a-entity>

  </a-scene>
  <script>
    AFRAME.registerComponent('rotacion-crane', {
      init: function() {
        this.rotationSpeed = 5; // Grados por evento
        this.followMouse = false;
        window.addEventListener('keydown', this.onKeyDown.bind(this));

        window.addEventListener('mousedown', this.onMouseDown.bind(this));
        window.addEventListener("mousemove", this.onMouseMove.bind(this));
      },

      onMouseDown: function(event) {
        this.followMouse = true;
      },

      onMouseMove: function(event) {

        if (!this.followMouse) return;
        const movingBox = document.querySelector('#movingBox');
        if (!movingBox) return;
        let currentRotation = movingBox.getAttribute('rotation');

        // Cambia la rotación en función de la posición del mouse
        currentRotation.y = (event.clientX / window.innerWidth) * -720; // Normaliza a 0-360 grados
        movingBox.setAttribute('rotation', currentRotation);
      },


      onKeyDown: function(event) {
        if (event.key === "Escape") {
          this.followMouse = false;
          return;
        }

        //-----------
        const craneArm = document.querySelector('#craneArm');
        if (!craneArm) return;

        let currentRotations = craneArm.getAttribute('rotation');

        if (event.key.toUpperCase() === 'O') {
          currentRotations.x += this.rotationSpeed;
          craneArm.setAttribute('rotation', currentRotations);
        } else if (event.key.toUpperCase() === 'L') {
          currentRotations.x -= this.rotationSpeed;
          craneArm.setAttribute('rotation', currentRotations);
        }

        //-----------
        const movingBox = document.querySelector('#movingBox');
        if (!movingBox) return;

        let currentRotation = movingBox.getAttribute('rotation');

        if (event.key.toUpperCase() === 'Q') {
          currentRotation.y += this.rotationSpeed;
          movingBox.setAttribute('rotation', currentRotation);
        } else if (event.key.toUpperCase() === 'E') {
          currentRotation.y -= this.rotationSpeed;
          movingBox.setAttribute('rotation', currentRotation);
        }
      }
    });

    // -----------------------------------------------------------
    function pegarDonutADraga() {
      const donut = document.querySelector('.donut');

      if (!donut) return;

      donut.setAttribute('ammo-constraint', 'type: lock; target: #craneArm; pivot: 0 0 0; axis: 0 1 0');

    }

    function soltar() {
      document.querySelector('.donut').removeAttribute('ammo-constraint');
    }
    // -----------------------------------------------------------
    function jumpDonut(donut) {
      window.addEventListener('keydown', function(event) {
        if (event.code === 'Space') {
          if (donut.getAttribute('ammo-constraint')) {
            soltar();
            console.log('separado!!!!');
          }
          const el = donut;

          const physicsComponent = el.components['ammo-body'];
          const body = physicsComponent.body;

          body.activate();

          const craneArm = document.querySelector('#craneArm');
          const direction = new THREE.Vector3();
          craneArm.object3D.getWorldDirection(direction);
          direction.normalize();

          const forwardForce = -5;
          const upwardForce = 10;

          const impulse = new Ammo.btVector3(0, upwardForce, direction.z * forwardForce);
          const relPos = new Ammo.btVector3(0, 0, 0);
          body.applyImpulse(impulse, relPos);

          // Limpia memoria temporal
          Ammo.destroy(impulse);
          Ammo.destroy(relPos);
        }
      });
    }

    // -----------------------------------------------------------
    window.addEventListener('keydown', function(event) {
      if (event.key.toLowerCase() === 'v') {
        const el = document.querySelector('.donut');
        const craneArm = document.querySelector('#craneArm');
        const offset = new THREE.Vector3(0, 1, 0);
        const worldOffset = craneArm.object3D.localToWorld(offset);
        const newPos = {
          x: worldOffset.x,
          y: worldOffset.y,
          z: worldOffset.z
        };


        // Mover visualmente
        el.setAttribute('position', newPos);

        el.setAttribute('rotation', '90 0 0');


        // Si tiene física con Ammo.js
        if (el.body) {
          el.body.setLinearVelocity(new Ammo.btVector3(0, 0, 0));
          el.body.setAngularVelocity(new Ammo.btVector3(0, 0, 0));

          const transform = new Ammo.btTransform();
          transform.setIdentity();
          transform.setOrigin(new Ammo.btVector3(newPos.x, newPos.y, newPos.z));

          const euler = new THREE.Euler(THREE.MathUtils.degToRad(90), 0, 0);
          const quat = new THREE.Quaternion().setFromEuler(euler);
          transform.setRotation(new Ammo.btQuaternion(quat.x, quat.y, quat.z, quat.w));


          el.body.setWorldTransform(transform);
          el.body.getMotionState().setWorldTransform(transform);
        }
        setTimeout(() => {
          const physicsComponent = el.components['ammo-body'];
          if (physicsComponent) {
            physicsComponent.body.activate();
          }

          pegarDonutADraga();
          console.log('Donut unido al brazo');
        }, 50);
      }
    });

    // -----------------------------------------------------------


    AFRAME.registerComponent('spawn-on-click', {
      init: function() {
        this.el.addEventListener('click', () => {
          const scene = document.querySelector('a-scene');
          const craneArm = document.querySelector('#craneArm');
          const offset = new THREE.Vector3(0, 1, 0);
          const worldOffset = craneArm.object3D.localToWorld(offset);
          const newPos = {
            x: worldOffset.x,
            y: worldOffset.y,
            z: worldOffset.z
          };

          if (!document.querySelector('.donut')) {
            // ⛏️ CLONAR DESDE TEMPLATE DE FORMA CORRECTA
            const donut = NAF.utils.getNetworkedTemplate('#donut-template');
            donut.setAttribute('position', newPos);
            donut.setAttribute('material', 'color: #' + Math.floor(Math.random() * 16777215).toString(16));

            scene.appendChild(donut);

            // Espera a que esté cargado en el DOM y tenga física
            donut.addEventListener('body-loaded', () => {
              pegarDonutADraga();
              jumpDonut(donut);
            });
          }
        });
      }
    });
    /* AFRAME.registerComponent('spawn-on-click', {
      init: function() {
        this.el.addEventListener('click', () => {
          const scene = document.querySelector('a-scene');

          const craneArm = document.querySelector('#craneArm');
          const offset = new THREE.Vector3(0, 1, 0);
          const worldOffset = craneArm.object3D.localToWorld(offset);
          const newPos = {
            x: worldOffset.x,
            y: worldOffset.y,
            z: worldOffset.z
          };

          if (!document.querySelector('.donut')) {
            const donut = document.createElement('a-torus');
            donut.setAttribute('networked', 'template:#donut-template');
            donut.setAttribute('class', 'donut grabbable aro');
            donut.setAttribute('position', newPos);
            donut.setAttribute('material', 'color: #' + Math.floor(Math.random() * 16777215).toString(16));
            donut.setAttribute('rotation', '90 0 0');
            // No necesitas configurar física manual aquí, ya está en la plantilla
            scene.appendChild(donut);



            pegarDonutADraga();
            jumpDonut(donut);
          }
        });
      }
    });
  */
  </script>

</body>

</html>