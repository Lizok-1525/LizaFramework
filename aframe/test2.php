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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.8.1/socket.io.min.js"></script>
  <script src="https://naf-examples.glitch.me/easyrtc/easyrtc.js"></script>
  <script src="https://naf-examples.glitch.me/dist/networked-aframe.js"></script>
  <script src="https://naf-examples.glitch.me/js/spawn-in-circle.component.js"></script>
  <script>
    function onConnect() {
      console.log('onConnect', new Date());
    }
    NAF.schemas.getComponentsOriginal = NAF.schemas.getComponents;
    NAF.schemas.getComponents = (template) => {
      if (!NAF.schemas.hasTemplate('#avatar-template')) {
        NAF.schemas.add({
          template: '#avatar-template',
          components: [{
              component: 'position',
              requiresNetworkUpdate: NAF.utils.vectorRequiresUpdate(0.001)
            },
            {
              component: 'rotation',
              requiresNetworkUpdate: NAF.utils.vectorRequiresUpdate(0.5)
            },
            {
              selector: '.head',
              component: 'material',
              property: 'color'
            }
          ]
        });
      }

      if (!NAF.schemas.hasTemplate('#donut-template')) {
        NAF.schemas.add({
          template: '#donut-template',
          components: [{
              component: 'position',
              requiresNetworkUpdate: NAF.utils.vectorRequiresUpdate(0.01)
            },
            {
              component: 'rotation',
              requiresNetworkUpdate: NAF.utils.vectorRequiresUpdate(0.1)
            },
            {
              component: 'material',
              property: 'color'
            }
          ]
        });
      }

      const components = NAF.schemas.getComponentsOriginal(template);
      return components;
    };
  </script>
  <script src="https://cdn.jsdelivr.net/npm/aframe-randomizer-components@3.0.2/dist/aframe-randomizer-components.min.js"></script>
</head>

<body>
  <a-scene networked-scene="room: basic; debug: true; adapter: wseasyrtc;" mouse-grab physics="driver: ammo; gravity: -10; debug: false" rotacion-crane stick-checker>

    <a-assets>
      <template id="avatar-template">
        <a-entity>
          <a-box class="head" color="#FFF" depth="0.3" height="0.3" width="0.3"></a-box>
        </a-entity>
      </template>
      <template id="donut-template">
        <a-torus class="donut grabbable aro"
          rotation="90 0 0"
          radius="0.3"
          radius-tubular="0.05"
          segments-radial="8"
          segments-tubular="12"
          ammo-body="type: dynamic; restitution: 0.7; disableDeactivation: false; linearDamping: 0.1; angularDamping: 0.1; mass: 1;"
          ammo-shape="type: hull"
          networked="template:#donut-template">
        </a-torus>
      </template>

      <!-- /Templates -->
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

    <!--   -->
    <a-entity id="player" networked="template:#avatar-template;" spawn-in-circle="radius:3" visible="true" ammo-body="type: kinematic; mass: 1;" ammo-constraint="type: lock; target: #mainCamera; pivot: 0 0 0; axis: 0 1 0">
      <a-box id="movingBox" material=" opacity: 0.7; transparent: true" position="0 0 -1" rotation="0 0 0" depth="1.8" height="2" width="2"
        ammo-body="type: kinematic;" ammo-shape="type: box;" wasd-controls>
        <a-box id="craneArm" material=" opacity: 0.7; transparent: true" position="0 1.5 -1" rotation="-30 0 0" depth="0.6" height="1.5" width="0.7"
          ammo-body="type: kinematic;" ammo-shape="type: box;" rotacion-crane></a-box>
        <a-entity camera id="mainCamera" networked="template:#avatar-template;" position="0 1.6 0.8" cursor="rayOrigin: mouse"
          raycaster="objects: .grabbable, .clickable"></a-entity>
      </a-box>
    </a-entity>

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
            const donut = document.createElement('a-torus');

            donut.setAttribute('networked', 'template:#donut-template');
            donut.setAttribute('class', 'donut grabbable aro');
            donut.setAttribute('position', newPos);
            donut.setAttribute('rotation', '90 0 0');

            // Color aleatorio
            donut.setAttribute('material', 'color: #' + Math.floor(Math.random() * 16777215).toString(16));

            // Físicas
            donut.setAttribute('ammo-body', 'type: dynamic; restitution: 0.7; disableDeactivation: false; linearDamping: 0.1; angularDamping: 0.1; mass: 1;');
            donut.setAttribute('ammo-shape', 'type: hull');

            scene.appendChild(donut);
            pegarDonutADraga();
            jumpDonut(donut);
          }
        });
      }
    });

    NAF.connection.onConnect(onConnect);
  </script>

</body>

</html>