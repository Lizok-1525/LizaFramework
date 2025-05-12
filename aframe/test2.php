<html>

<head>

  <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
  <script src="https://unpkg.com/aframe-environment-component@1.5.0/dist/aframe-environment-component.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/MozillaReality/ammo.js@8bbc0ea/builds/ammo.wasm.js"></script>
  <script src="https://c-frame.github.io/aframe-physics-system/dist/aframe-physics-system.js"></script>
  <script src="https://c-frame.github.io/aframe-physics-system/examples/components/force-pushable.js"></script>
  <script src="https://c-frame.github.io/aframe-physics-system/examples/components/grab.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
  <a-scene mouse-grab physics="driver: ammo; gravity: -9.8; debug: false" crane-rotation rotacion-crane disparar-aro>

    <a-box position="0 1 -11" width="4" height="2" depth="0.2" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
      ammo-shape="type: box;"></a-box>
    <a-box position="-2 1 -9" width="0.2" height="2" depth="4" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
      ammo-shape="type: box;"></a-box>
    <a-box position="2 1 -9" width="0.2" height="2" depth="4" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
      ammo-shape="type: box;"></a-box>
    <a-box position="0 0.5 -7" width="4" height="1.5" depth="0.2" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;" ammo-shape="type: box;"></a-box>

    <!-- Botón para añadir piezas-->
    <a-box id="boton" position="2 2 -2" depth="1" height="0.2" width="0.2" color="#F00"
      class="spawn-rings clickable" shadow
      ammo-body="type: static;"
      ammo-shape="type: box;">
    </a-box>

    <a-box id="movingBox" material=" opacity: 0.7; transparent: true" position="0 0 -1" rotation="0 0 0" depth="1.8" height="2" width="2"
      ammo-body="type: kinematic;" ammo-shape="type: box;" wasd-controls>
      <a-box id="craneArm" material=" opacity: 0.7; transparent: true" position="0 1.5 -1" rotation="-30 0 0" depth="0.6" height="1.5" width="0.7"
        ammo-body="type: kinematic;" ammo-shape="type: box;">
      </a-box>
      <a-entity camera id="mainCamera" position="0 1.6 0.5" cursor="rayOrigin: mouse"
        raycaster="objects: .grabbable, .clickable"></a-entity>
    </a-box>

    <a-box
      position="0 0 0"
      width="200"
      depth="200"
      height="0.8"
      color="transparent"
      visible="false"
      ammo-body="type: static"
      ammo-shape="type: box">
    </a-box>

    <a-entity environment></a-entity>

  </a-scene>

  <script>
    AFRAME.registerComponent('mouse-grab', {
      init: function() {
        let grabbed = null;
        let offset = new THREE.Vector3();

        const scene = this.el.sceneEl;
        const camera = scene.camera;
        const raycaster = new THREE.Raycaster();

        const onMouseDown = (e) => {
          const mouse = new THREE.Vector2(
            (e.clientX / window.innerWidth) * 2 - 1,
            -(e.clientY / window.innerHeight) * 2 + 1
          );
          raycaster.setFromCamera(mouse, camera);

          const intersects = raycaster.intersectObjects(
            Array.from(document.querySelectorAll('.grabbable')).map(el => el.object3D),
            true
          );

          if (intersects.length > 0) {
            const intersected = intersects[0].object.el;
            grabbed = intersected;

            // Convert to kinematic while holding
            grabbed.setAttribute('ammo-body', 'type: kinematic');

            const pos = new THREE.Vector3().copy(intersects[0].point);
            offset.copy(pos).sub(grabbed.object3D.getWorldPosition(new THREE.Vector3()));
          }
        };

        const onMouseUp = () => {
          if (grabbed) {
            grabbed.setAttribute('ammo-body', 'type: dynamic');
            grabbed = null;
          }
        };

        const onMouseMove = (e) => {
          if (!grabbed) return;

          const mouse = new THREE.Vector2(
            (e.clientX / window.innerWidth) * 2 - 1,
            -(e.clientY / window.innerHeight) * 2 + 1
          );
          raycaster.setFromCamera(mouse, camera);
          const direction = new THREE.Vector3();
          raycaster.ray.direction.normalize();
          direction.copy(raycaster.ray.direction).multiplyScalar(2); // 2 units away
          const targetPos = raycaster.ray.origin.clone().add(direction).sub(offset);

          grabbed.object3D.position.copy(targetPos);
        };

        window.addEventListener('mousedown', onMouseDown);
        window.addEventListener('mouseup', onMouseUp);
        window.addEventListener('mousemove', onMouseMove);
      }
    });


    let currentRing = null;

    document.querySelector('.spawn-rings').addEventListener('click', () => {
      const crane = document.querySelector('#craneArm');
      const cranePos = new THREE.Vector3();
      crane.object3D.getWorldPosition(cranePos);

      // Si ya existe un aro, lo removemos primero (opcional)
      if (currentRing) {
        currentRing.parentNode.removeChild(currentRing);
        currentRing = null;
      }

      const ring = document.createElement('a-torus');
      ring.setAttribute('rotation', '90 0 0');
      ring.setAttribute('radius', '0.3');
      ring.setAttribute('radius-tubular', 0.05);
      ring.setAttribute('segments-radial', 8);
      ring.setAttribute('segments-tubular', 12);
      ring.setAttribute('color', '#' + Math.floor(Math.random() * 16777215).toString(16));
      ring.setAttribute('class', 'grabbable aro');
      ring.setAttribute('ammo-body', 'type: kinematic; isSleeping: true;');
      ring.setAttribute('ammo-shape', 'type: hull');
      ring.setAttribute('aplicar-fuerza-tecla', '');
      ring.setAttribute('force-pushable', '');
      ring.setAttribute('shadow', '');
      ring.setAttribute('position', {
        x: 0,
        y: 0.8, // Un poco más alto para evitar la intersección, ajusta según sea necesario
        z: 0
      });

      crane.appendChild(ring);
      currentRing = ring;
    });

    // -----------------------------------------------------------

    AFRAME.registerComponent('disparar-aro', {
      init: function() {
        window.addEventListener('keydown', (e) => {
          if (e.code === 'Space' && currentRing) {
            const ring = currentRing;
            currentRing = null;

            const craneArm = document.querySelector('#craneArm');

            // Obtener dirección hacia donde apunta el craneArm
            const direction = new THREE.Vector3(0, 0, -1);
            craneArm.object3D.getWorldDirection(direction);
            direction.normalize();

            // Obtener posición global del craneArm
            const origin = new THREE.Vector3();
            craneArm.object3D.getWorldPosition(origin);

            // Mover el aro a esa posición (ya no lo dejamos hijo del brazo)
            ring.parentNode.removeChild(ring);
            document.querySelector('a-scene').appendChild(ring);
            ring.setAttribute('ammo-body', 'type: dynamic;');

            // Aplicar la fuerza al momento
            ring.addEventListener('body-loaded', () => {
              const force = direction.multiplyScalar(10); // Ajusta fuerza aquí
              ring.components['ammo-body'].applyForce(force, new THREE.Vector3(0, 0, 0));
            });
          }
        });
      }
    });

    //------------------------------------------------------------

    AFRAME.registerComponent('torus-cleanup', {
      tick: function() {
        // Revisa cada frame todos los torus
        document.querySelectorAll('a-torus').forEach(torus => {
          const pos = torus.object3D.position;
          if (pos.y < -5) {
            // Quita el cuerpo físico de Ammo.js si existe
            if (torus.hasAttribute('ammo-body')) {
              torus.removeAttribute('ammo-body'); // Destruye el cuerpo físico
            }

            // Elimina el torus de la escena
            torus.parentNode.removeChild(torus);
            contador--;
          }
        });
      }
    });

    // -----------------------------------------------------------
    AFRAME.registerComponent('crane-rotation', {
      init: function() {
        this.rotationSpeed = 5; // Grados por evento
        window.addEventListener('keydown', this.onKeyDown.bind(this));
      },

      onKeyDown: function(event) {
        const craneArm = document.querySelector('#craneArm');
        if (!craneArm) return;

        let currentRotation = craneArm.getAttribute('rotation');

        if (event.key.toUpperCase() === 'O') {
          currentRotation.x += this.rotationSpeed;
          craneArm.setAttribute('rotation', currentRotation);
        } else if (event.key.toUpperCase() === 'L') {
          currentRotation.x -= this.rotationSpeed;
          craneArm.setAttribute('rotation', currentRotation);
        }
      }
    });

    AFRAME.registerComponent('rotacion-crane', {
      init: function() {
        this.rotationSpeed = 5; // Grados por evento
        window.addEventListener('keydown', this.onKeyDown.bind(this));
      },

      onKeyDown: function(event) {
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
  </script>

</body>

</html>