<html>

<head>

    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
    <script src="https://unpkg.com/aframe-environment-component@1.5.0/dist/aframe-environment-component.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/MozillaReality/ammo.js@8bbc0ea/builds/ammo.wasm.js"></script>
    <script src="https://c-frame.github.io/aframe-physics-system/dist/aframe-physics-system.js"></script>
    <script src="https://c-frame.github.io/aframe-physics-system/examples/components/force-pushable.js"></script>
    <script src="https://c-frame.github.io/aframe-physics-system/examples/components/grab.js"></script>

</head>

<body>
    <a-scene mouse-grab physics="driver: ammo; gravity: -9.8; debug: false">
        <!-- Player -->
        <a-entity camera look-controls wasd-controls position="0 1.6 0">
            <a-entity id="mouseCursor"
                cursor="rayOrigin: mouse"
                raycaster="objects: .grabbable, .clickable"
                material="color: red; shader: flat"
                geometry="primitive: ring; radiusInner: 0.01; radiusOuter: 0.02">
            </a-entity>
        </a-entity>

        <!--
        <a-plane position="0 0 -4" rotation="-90 0 0" width="100" height="100" color="#7BC8A4" ammo-body="type: static" ammo-shape></a-plane>

         Manos con controles -->
        <a-entity id="left-hand" ammo-body="type: kinematic; emitCollisionEvents: true" ammo-shape="type: sphere; fit: manual; sphereRadius: 0.02;"
            hand-controls="hand: left" grab></a-entity>
        <a-entity id="right-hand" ammo-body="type: kinematic; emitCollisionEvents: true" ammo-shape="type: sphere; fit: manual; sphereRadius: 0.02;"
            hand-controls="hand: right" grab></a-entity>

        <!-- Palo central -->
        <a-cylinder id="palo" position="0 1 -4" radius="0.1" height="2.5" color="#333" shadow
            ammo-body="type: static;"
            ammo-shape="type: cylinder;"></a-cylinder>
        <a-cylinder id="palo" position="3 1 -7" radius="0.1" height="2.5" color="#333" shadow
            ammo-body="type: static;"
            ammo-shape="type: cylinder;"></a-cylinder>
        <a-cylinder id="palo" position="-3 1 -7" radius="0.1" height="2.5" color="#333" shadow
            ammo-body="type: static;"
            ammo-shape="type: cylinder;"></a-cylinder>

        <!-- Botón para añadir piezas-->
        <a-box id="boton" position="0 0.2 -2" depth="0.2" height="0.2" width="1" color="#F00"
            class="clickable" shadow
            ammo-body="type: static;"
            ammo-shape="type: box;">
        </a-box>

        <!-- Toro (donut) interactivo -->
        <a-torus
            position="1 0.5 -4"
            rotation="90 0 0"
            radius="0.5"
            radius-tubular="0.1"
            segments-radial="16"
            segments-tubular="24"
            color="blue"
            class="grabbable"
            ammo-body="type: dynamic;"
            ammo-shape="type: hull;"
            force-pushable
            torus-respawn>
        </a-torus>


        <!-- Luces -->

        <a-entity light="type: ambient; color: #888"></a-entity>
        <a-entity light="type: directional; intensity: 0.5" position="-1 1 0.5"></a-entity>
        <a-entity environment="preset: forest; dressingAmount: 600"></a-entity>

        <a-box
            position="0 -0.1 0"
            width="200"
            depth="200"
            height="0.2"
            color="transparent"
            visible="false"
            ammo-body="type: static"
            ammo-shape="type: box">
        </a-box>
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
    </script>
    <!--  Botón para añadir piezas   -->
    <script>
        let contador = 0;

        document.querySelector('#boton').addEventListener('click', () => {
            const scene = document.querySelector('a-scene');
            const posiciones = [-2, 2]; // x: izquierda y derecha

            for (let i = 0; i < 2; i++) {
                const torus = document.createElement('a-torus');

                const x = posiciones[i]; // -1 o 1
                const z = -4; // misma z que el palo
                const y = 1;

                torus.setAttribute('position', `${x} ${y} ${z}`);
                torus.setAttribute('rotation', '90 0 0'); // ahora está de pie
                torus.setAttribute('radius', 0.7);
                torus.setAttribute('radius-tubular', 0.1);
                torus.setAttribute('segments-radial', 16);
                torus.setAttribute('segments-tubular', 24);

                torus.setAttribute('color', '#' + Math.floor(Math.random() * 16777215).toString(16));
                torus.setAttribute('class', 'grabbable');
                torus.setAttribute('ammo-body', 'type: dynamic; disableDeactivation: true; linearDamping: 0.9; angularDamping: 0.9');
                torus.setAttribute('ammo-shape', 'type: hacd;');
                torus.setAttribute('force-pushable', '');
                torus.setAttribute('shadow', '');
                scene.appendChild(torus);
                contador++;
            }
        });


        // Añade este componente a la escena para que funcione
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('a-scene').setAttribute('torus-respawn', '');
        });
    </script>
</body>

</html>