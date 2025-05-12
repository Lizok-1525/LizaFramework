<html>

<head>
    <?php include("../template/standard/metas.inc.php"); ?>
    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
    <script src="https://unpkg.com/aframe-environment-component@1.5.0/dist/aframe-environment-component.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/MozillaReality/ammo.js@8bbc0ea/builds/ammo.wasm.js"></script>
    <script src="https://c-frame.github.io/aframe-physics-system/dist/aframe-physics-system.js"></script>
    <script src="https://c-frame.github.io/aframe-physics-system/examples/components/force-pushable.js"></script>
    <script src="https://c-frame.github.io/aframe-physics-system/examples/components/grab.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        AFRAME.registerComponent('proyectil-lanzable', {
            schema: {
                force: {
                    type: 'number',
                    default: 10
                }
            },

            init: function() {
                const el = this.el;
                this.lanzado = false;

                window.addEventListener('keydown', (e) => {
                    if (e.code === 'Space' && !this.lanzado) {
                        this.lanzado = true;

                        el.setAttribute('ammo-body', {
                            type: 'dynamic',
                            mass: 1,
                            disableDeactivation: false
                        });

                        el.addEventListener('body-loaded', () => {
                            const direction = new THREE.Vector3(0, 0.2, -1); // dirección hacia adelante y un poco hacia arriba
                            direction.normalize().multiplyScalar(this.data.force);

                            const impulse = new Ammo.btVector3(direction.x, direction.y, direction.z);
                            const rel_pos = new Ammo.btVector3(0, 0, 0);

                            el.body.setLinearVelocity(impulse); // prueba usar velocidad directa
                            el.body.applyImpulse(impulse, rel_pos);
                        });
                    }
                });
            }
        });
    </script>
</head>

<body>
    <a-scene physics="driver: ammo; debug: true; gravity: -9.8">
        <!-- Suelo (plano estático) -->
        <a-box position="0 -1 0" width="10" height="0.2" depth="10" ammo-body="type: static" ammo-shape="type: box;" color="#7BC8A4"></a-box>

        <!-- Caja (dinámica) -->
        <a-entity id="torus"
            geometry="primitive: torus; radius: 0.3; radiusTubular: 0.05"
            material="color: red"
            position="0 1.5 -2"
            scale="1 1 1"
            proyectil-lanzable="force: 20"
            ammo-shape="type: hull">
        </a-entity>

        <!-- Cámara -->
        <a-entity camera look-controls wasd-controls position="0 2 5"></a-entity>
    </a-scene>
</body>

</html>