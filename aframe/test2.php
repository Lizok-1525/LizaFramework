<html>

<head>
  <?php include("../template/standard/metas.inc.php"); ?>

  <title>Pruebas para juego</title>
  <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
  <script src="https://unpkg.com/aframe-environment-component@1.5.0/dist/aframe-environment-component.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/MozillaReality/ammo.js@8bbc0ea/builds/ammo.wasm.js"></script>
  <script src="https://c-frame.github.io/aframe-physics-system/dist/aframe-physics-system.js"></script>
  <script src="https://c-frame.github.io/aframe-physics-system/examples/components/force-pushable.js"></script>
  <script src="https://c-frame.github.io/aframe-physics-system/examples/components/grab.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
  <a-scene mouse-grab physics="driver: ammo; gravity: -9.8; debug: false" rotacion-crane stick-checker>

    <a-box position="0 1 -11" width="4" height="2" depth="0.2" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
      ammo-shape="type: box;"></a-box>
    <a-box position="-2 1 -9" width="0.2" height="2" depth="4" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
      ammo-shape="type: box;"></a-box>
    <a-box position="2 1 -9" width="0.2" height="2" depth="4" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
      ammo-shape="type: box;"></a-box>
    <a-box position="0 0.5 -7" width="4" height="1.5" depth="0.2" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;" ammo-shape="type: box;"></a-box>

    <!-- Botón para añadir piezas
    <a-box id="boton" position="2 2 -2" depth="1" height="0.2" width="0.2" color="#F00"
      class="spawn-rings clickable" shadow
      ammo-body="type: static;"
      ammo-shape="type: box;">
    </a-box>-->

    <a-torus id="donut" rotation="90 0 0" radius="0.3" radius-tubular="0.05"
      segments-radial="8" segments-tubular="12"
      color="red" force-pushable>
    </a-torus>

    <a-box id="movingBox" material=" opacity: 0.7; transparent: true" position="0 0 -1" rotation="0 0 0" depth="1.8" height="2" width="2"
      ammo-body="type: kinematic;" ammo-shape="type: box;" wasd-controls>
      <a-box id="craneArm" material=" opacity: 0.7; transparent: true" position="0 1.5 -1" rotation="-30 0 0" depth="0.6" height="1.5" width="0.7"
        ammo-body="type: kinematic;" ammo-shape="type: box;" rotacion-crane></a-box>
      <a-entity camera id="mainCamera" position="0 1.6 0.8" cursor="rayOrigin: mouse"
        raycaster="objects: .grabbable, .clickable, force-pushable"></a-entity>
    </a-box>

    <a-box
      position="0 0 0"
      width="200"
      depth="200"
      height="1"
      material=" opacity: 0.7; transparent: true"
      visible="false"
      ammo-body="type: static"
      ammo-shape="type: box">
    </a-box>

    <a-entity environment></a-entity>

  </a-scene>

  <script>
    /*
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
*/

    // -----------------------------------------------------------
    /*
        document.querySelector('.spawn-rings').addEventListener('click', () => {

          const scene = document.querySelector('a-scene');

          const ring = document.createElement('a-torus');
          ring.setAttribute('rotation', '90 0 0');
          ring.setAttribute('radius', '0.3');
          ring.setAttribute('radius-tubular', 0.05);
          ring.setAttribute('segments-radial', 8);
          ring.setAttribute('segments-tubular', 12);
          ring.setAttribute('color', '#' + Math.floor(Math.random() * 16777215).toString(16));
          ring.setAttribute('class', 'grabbable aro');
          ring.setAttribute('ammo-body', 'type: dynamic; disableDeactivation: false; linearDamping: 0.1; angularDamping: 0.1; mass: 1;');
          ring.setAttribute('ammo-shape', 'type: hull');
          ring.setAttribute('force-pushable', 'force: 20');
          ring.setAttribute('stick-to-crane', '');
          ring.setAttribute('position', {
            x: 1,
            y: 0.5, // Un poco más alto para evitar la intersección, ajusta según sea necesario
            z: 1
          });

          scene.appendChild(ring);
          currentRing = ring;
        });
    */

    // -----------------------------------------------------------
    AFRAME.registerComponent('jump-on-space', {
      init: function() {
        const el = this.el;

        el.addEventListener('body-loaded', () => {
          //console.log('[jump-on-space] Body loaded, ready to jump');

          window.addEventListener('keydown', function(event) {
            /*
            if (event.code === 'Space') {
              const physicsComponent = el.components['ammo-body'];


              const body = physicsComponent.body;

              // IMPORTANTE: desactiva la desactivación si no lo has hecho
              body.activate();

              // Aplica impulso vertical hacia arriba
              const impulse = new Ammo.btVector3(0, 5, 0);
              const relPos = new Ammo.btVector3(0, 0, 0);
              body.applyImpulse(impulse, relPos);

              // Limpia memoria temporal
              Ammo.destroy(impulse);
              Ammo.destroy(relPos);
            }*/
          });
        });
      }
    });

    // -----------------------------------------------------------

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

    /*
    AFRAME.registerComponent('random-position-on-load', {
      init: function() {
        // Generar una posición aleatoria dentro de un rango
        const globalPos = new THREE.Vector3(
          Math.random() * 10 - 5,
          Math.random() * 5 + 1,
          Math.random() * 10 - 5
        );

        console.log(globalPos);
        if (this.el.object3D.parent) {
          this.el.object3D.position.copy(
            this.el.object3D.parent.worldToLocal(globalPos)
          );
        } else {
          this.el.object3D.position.copy(globalPos);
        }

      }
    });

*/

    /*
        const posX = Math.random() * 10 - 5;
        const posY = Math.random() * 5 + 1;
        const posZ = Math.random() * 10 - 5;
         x: 1,
            y: 0.5, // Un poco más alto para evitar la intersección, ajusta según sea necesario
            z: 1
    */

    const posX = Math.random() * 10 - 5;
    const posY = Math.random() * 5 + 1;
    const posZ = Math.random() * 10 - 5;

    console.log(posX, posY, posZ);

    window.addEventListener('load', () => {
      const donut = document.getElementById('donut');

      // Establecer la posición del donut
      donut.setAttribute('position', {
        x: posX,
        y: posY,
        z: posZ
      });

      // Establecer el cuerpo después de la forma
      donut.setAttribute('ammo-body', 'type: dynamic; disableDeactivation: false; linearDamping: 0.1; angularDamping: 0.1; mass: 1;');
      //donut.setAttribute('ammo-body', 'type: kinematic; mass: 0;');

      // Asegúrate de agregar la forma antes de establecer el cuerpo
      donut.setAttribute('ammo-shape', 'type: hull');
      jumpDonut(donut); // Llama a la función de salto aquí

    });

    function jumpDonut(donut) {
      window.addEventListener('keydown', function(event) {
        if (event.code === 'Space') {
          const el = donut;

          const physicsComponent = el.components['ammo-body'];
          const body = physicsComponent.body;

          body.activate();

          // Aplica impulso vertical hacia arriba
          const randomX = (Math.random() - 0.5) * 10; // Valor aleatorio entre -5 y 5
          const randomZ = (Math.random() - 0.5) * 10; // Valor aleatorio entre -5 y 5
          const impulse = new Ammo.btVector3(randomX, 6, randomZ);
          const relPos = new Ammo.btVector3(0, 0, 0);
          body.applyImpulse(impulse, relPos);

          // Limpia memoria temporal
          Ammo.destroy(impulse);
          Ammo.destroy(relPos);

        }
      });
    }
  </script>

</body>

</html>