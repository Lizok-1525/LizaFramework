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
  <a-scene mouse-grab physics="driver: ammo; gravity: -9.8; debug: false" score-detector crane-rotation object-snapper>
    <!-- Player -->
    <a-entity camera look-controls wasd-controls position="0 1.6 0">
      <a-entity id="mouseCursor"
        cursor="rayOrigin: mouse"
        raycaster="objects: .grabbable, .clickable"
        material="color: red; shader: flat"
        geometry="primitive: ring; radiusInner: 0.01; radiusOuter: 0.02">
      </a-entity>
    </a-entity>


    <a-box position="0 1 -11" width="4" height="2" depth="0.2" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
      ammo-shape="type: box;"></a-box>
    <a-box position="-2 1 -9" width="0.2" height="2" depth="4" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
      ammo-shape="type: box;"></a-box>
    <a-box position="2 1 -9" width="0.2" height="2" depth="4" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;"
      ammo-shape="type: box;"></a-box>
    <a-box position="0 0.5 -7" width="4" height="1.5" depth="0.2" src="https://i.imgur.com/mYmmbrp.jpg" ammo-body="type: static;" ammo-shape="type: box;"></a-box>

    <!-- Botón para añadir piezas-->
    <a-box id="boton" position="0 0.2 -2" depth="0.2" height="0.2" width="1" color="#F00"
      class="spawn-rings clickable" shadow
      ammo-body="type: static;"
      ammo-shape="type: box;">
    </a-box>

    <a-box id="craneArm" color="yellow" position="0 1.6 -2" rotation="-30 0 0" depth="0.6" height="2" width="0.7" ammo-body="type: static;" ammo-shape="type: box;" wasd-controls></a-box>

    <a-box id="movingBox" color="yellow" position="0 0 -1" depth="1.8" height="2" width="2" ammo-body="type: static;" ammo-shape="type: box;" wasd-controls></a-box>

    <a-camera id="mainCamera" look-controls wasd-controls="false">
      <a-entity position="0 0 0" camera-follow="target: #movingBox"></a-entity>
    </a-camera>


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
    AFRAME.registerComponent('camera-follow', {
      schema: {
        target: {
          type: 'selector'
        }
      },

      init: function() {
        this.targetPosition = new THREE.Vector3();
        this.cameraPosition = new THREE.Vector3();
      },

      tick: function() {
        const target = this.data.target.object3D;
        const camera = this.el.object3D;

        if (target) {
          // Obtener la posición mundial del objetivo
          target.getWorldPosition(this.targetPosition);

          // Establecer la posición de la entidad de seguimiento para que coincida con el objetivo
          this.el.setAttribute('position', this.targetPosition);
        }
      }
    });

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

    //-----------------------------------------------------------

    /**/
    let contador = 0;
    document.querySelector('.spawn-rings').addEventListener('click', () => {
      const scene = document.querySelector('a-scene');
      const spawnPosition = {
        x: -3,
        y: 2,
        z: -5
      }; // Posición inicial diferente

      if (contador >= 20) return;
      const torus = document.createElement('a-torus');
      torus.setAttribute('position', spawnPosition);
      torus.setAttribute('rotation', '90 0 0');
      torus.setAttribute('radius', '0.3');
      torus.setAttribute('radius-tubular', 0.05);
      torus.setAttribute('segments-radial', 8);
      torus.setAttribute('segments-tubular', 12);
      torus.setAttribute('color', '#' + Math.floor(Math.random() * 16777215).toString(16));
      torus.setAttribute('class', 'grabbable aro');
      torus.setAttribute('ammo-body', 'type: dynamic; disableDeactivation: false; linearDamping: 0.1; angularDamping: 0.1; ');
      torus.setAttribute('ammo-shape', 'type: hull');
      torus.setAttribute('force-pushable', '');
      torus.setAttribute('shadow', '');
      scene.appendChild(torus);
      contador++;
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

    AFRAME.registerComponent('score-detector', {
      schema: {
        // Extremos de la caja en X, Z y la altura máxima para contar
        minX: {
          type: 'number',
          default: -4
        },
        maxX: {
          type: 'number',
          default: 4
        },
        minZ: {
          type: 'number',
          default: -11
        },
        maxZ: {
          type: 'number',
          default: -7
        },
        maxY: {
          type: 'number',
          default: 1.8
        } // por encima de aquí ya no cuentan
      },

      init: function() {
        this.score = 0;
      },

      tick: function() {
        const els = this.el.sceneEl.querySelectorAll('.aro');
        els.forEach(el => {
          if (el.getAttribute('scored')) return; // ya puntuado
          // Obtén posición global
          const pos = new THREE.Vector3();
          el.object3D.getWorldPosition(pos);

          // Comprueba si está dentro de tus límites
          if (
            pos.x > this.data.minX && pos.x < this.data.maxX &&
            pos.z > this.data.minZ && pos.z < this.data.maxZ &&
            pos.y < this.data.maxY
          ) {
            el.setAttribute('scored', true);
            this.score++;
            document.querySelector('#puntos').setAttribute('value', `Puntos: ${this.score}`);
          }
        });
        if (this.score === 7) {
          const boton2 = document.querySelector('#boton2');
          if (boton2) {
            boton2.setAttribute('visible', 'true');

          }
        }
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

    AFRAME.registerComponent('object-snapper', {
      init: function() {
        window.addEventListener('keydown', this.onKeyDown.bind(this));
      },

      onKeyDown: function(event) {
        if (event.code === 'Space') {
          const snappedRing = document.querySelector('.aro[is-snapped]');
          const craneArm = document.querySelector('#craneArm');

          if (snappedRing && snappedRing.parentNode === craneArm) {
            // Despegar del brazo
            snappedRing.removeAttribute('parent');
            snappedRing.removeAttribute('is-snapped');
            snappedRing.setAttribute('ammo-body', 'type: dynamic');

            // Aplicar una fuerza para que salte
            const impulse = new CANNON.Vec3(0, 5, 0); // Fuerza hacia arriba
            const worldPoint = new CANNON.Vec3();
            snappedRing.components['ammo-body'].body.applyImpulse(impulse, worldPoint);
          }
        }
      }
    });
  </script>

</body>

</html>