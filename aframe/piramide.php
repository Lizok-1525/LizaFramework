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

</head>

<body>
    <a-scene mouse-grab physics="driver: ammo; gravity: -9.8; debug: false" score-detector contador-bloques>
        <!-- Player -->
        <a-entity camera look-controls wasd-controls position="0 1.6 0">
            <a-entity id="mouseCursor"
                cursor="rayOrigin: mouse"
                raycaster="objects: .grabbable, .clickable"
                material="color: red; shader: flat"
                geometry="primitive: ring; radiusInner: 0.01; radiusOuter: 0.02">
            </a-entity>
        </a-entity>


        <a-assets>
            <a-torus id="torus-template"
                radius="0.7"
                radius-tubular="0.1"
                segments-radial="8"
                segments-tubular="12"
                visible="false"
                ammo-body="type: dynamic; disableDeactivation: false; linearDamping: 0.9; angularDamping: 0.9"
                ammo-shape="type: hacd"
                class="grabbable"
                force-pushable
                shadow></a-torus>
            <a-box id="bloque-template"
                width="0.5"
                height="0.5"
                depth="0.5"
                visible="false"
                ammo-body="type: dynamic; disableDeactivation: false; linearDamping: 0.1; angularDamping: 0.1"
                ammo-shape="type: box"
                class="grabbable bloque"
                force-pushable
                shadow></a-box>
        </a-assets>

        <a-box class="load-level clickable" id="boton1" position="-2 0.2 -2" rotation="0 30 0" depth="1" width="2" height="1"
            material="color: #FFF; opacity: 0.7; transparent: true" shadow
            ammo-body="type: static;"
            ammo-shape="type: box;"
            data-type="level_1">
        </a-box>

        <!-- Botón para subir el nivel-->
        <a-box class="load-level clickable" id="boton2" position="-3 0.2 0" rotation="0 30 0" depth="1" width="2" height="1" visible="false"
            material="color: #0FC; opacity: 0.7; transparent: true;" shadow
            ammo-body="type: static;"
            ammo-shape="type: box;"
            data-type="level_2">
        </a-box>

        <a-box class="load-level clickable" id="boton3" position="-3 0.2 1.5" rotation="0 30 0" depth="1" width="2" height="1" visible="false"
            material="color: #0F0; opacity: 0.7; transparent: true;" shadow
            ammo-body="type: static;"
            ammo-shape="type: box;"
            data-type="level_3">
        </a-box>

        <a-text id="puntos" value="Para empezar el juego haz clic al botton gris" position="-1 2 -8" color="yellow" width="4"></a-text>

        <a-entity id="content" position="0 0 0"></a-entity>

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

        <a-entity environment="preset: arches; ground: hills; shadow: true"></a-entity>

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

        //-----------------------------------------------------------


        $('.load-level').click(function() {
            const type = $(this).data('type');

            $.ajax({
                url: 'game.php',
                type: 'GET',
                data: {
                    type: type
                },
                success: function(data) {

                    $('#content').empty();
                    // Limpia el contenido anterior
                    document.querySelectorAll('.aro').forEach(el => {
                        el.parentNode.removeChild(el);
                    }); // Limpia todo el contenido anterior
                    document.querySelectorAll('.bloque').forEach(el => {
                        el.parentNode.removeChild(el);
                    });
                    $('#content').append(data);
                    document.querySelector('#puntos').setAttribute('value', `Puntos: 0`);
                    document.querySelector('#boton1').setAttribute('visible', 'false');


                    if (type === 'level_1') {
                        let contador = 0;
                        /**/
                        document.querySelector('.spawn-rings').addEventListener('click', () => {
                            const scene = document.querySelector('a-scene');
                            const posiciones = [-1, 1]; // x: izquierda y derecha


                            if (contador >= 20) return;
                            for (let i = 0; i < 2; i++) {
                                const torus = document.createElement('a-torus');

                                const x = posiciones[i]; // -1 o 1
                                const z = -4; // misma z que el palo
                                const y = 1;

                                const baseRadius = 1;

                                const scaleFactor = 0.2; // cuánto se reduce por pieza añadida  
                                // Math.max(radius, 0.3)

                                const radius = baseRadius - scaleFactor * contador;

                                torus.setAttribute('position', `${x} ${y} ${z}`);
                                torus.setAttribute('rotation', '90 0 0'); // ahora está de pie
                                torus.setAttribute('radius', '0.3'); // evita que sea negativo

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

                    } else if (type === 'level_2') {
                        let contadorBloques = 0;
                        document.querySelector('#puntos').setAttribute('value', `Empezamos nivel 2`);
                        document.querySelector('#boton2').setAttribute('visible', 'false');
                        const generateBlockButton = document.querySelector('#boton-bloque');
                        if (generateBlockButton) {
                            generateBlockButton.addEventListener('click', () => {
                                const scene = document.querySelector('a-scene');

                                const bloque = document.createElement('a-box');

                                const x = Math.random() * 2 - 1; // Posición x aleatoria
                                const y = 0 + contadorBloques * 0.5; // Apilándose hacia arriba (ajusté la altura inicial)
                                const z = -6; // Ajusté la posición Z para que esté cerca de la base

                                bloque.setAttribute('position', `${x} ${y} ${z}`);
                                console.log('posición', x, y, z);
                                bloque.setAttribute('color', '#' + Math.floor(Math.random() * 16777215).toString(16));
                                bloque.setAttribute('class', 'grabbable bloque');
                                bloque.setAttribute('width', '0.5');
                                bloque.setAttribute('height', '0.5');
                                bloque.setAttribute('depth', '0.5');
                                bloque.setAttribute('ammo-body', 'type: dynamic; disableDeactivation: false; linearDamping: 0.1; angularDamping: 0.1; ');
                                bloque.setAttribute('ammo-shape', 'type: box');
                                bloque.setAttribute('force-pushable', '');
                                bloque.setAttribute('shadow', '');
                                scene.appendChild(bloque);
                                contadorBloques++;
                            });
                        }


                    } else if (type === 'level_3') {

                        let contadorFiguras = 0;
                        document.querySelector('#puntos').setAttribute('value', `Empezamos nivel 3`);
                        document.querySelector('#boton2').setAttribute('visible', 'false');
                        const generateShapeButton = document.querySelector('#boton-figura');

                        if (generateShapeButton) {
                            generateShapeButton.addEventListener('click', () => {
                                const scene = document.querySelector('a-scene');
                                const tipos = ['box', 'cylinder', 'prisma'];
                                const tipo = tipos[Math.floor(Math.random() * tipos.length)];
                                const figura = document.createElement('a-entity');

                                const x = (Math.random() - 0.5) * 4;
                                const y = 1.5;
                                const z = -8;

                                figura.setAttribute('position', `${x} ${y} ${z}`);
                                figura.setAttribute('class', 'grabbable figura');
                                figura.setAttribute('color', '#' + Math.floor(Math.random() * 16777215).toString(16));
                                figura.setAttribute('ammo-body', 'type: dynamic; disableDeactivation: false; linearDamping: 0.1; angularDamping: 0.1');
                                figura.setAttribute('force-pushable', '');
                                figura.setAttribute('shadow', '');

                                if (tipo === 'box') {
                                    figura.setAttribute('geometry', 'primitive: box; width: 1; height: 0.5; depth: 0.5');
                                    figura.setAttribute('ammo-shape', 'type: box');
                                } else if (tipo === 'cylinder') {
                                    figura.setAttribute('geometry', 'primitive: cylinder; radius: 0.25; height: 1');
                                    figura.setAttribute('ammo-shape', 'type: cylinder');
                                } else if (tipo === 'prisma') {
                                    figura.setAttribute('geometry', 'primitive: cone; radiusBottom: 0.3; radiusTop: 0.3; height: 1');
                                    figura.setAttribute('ammo-shape', 'type: cone');
                                }

                                scene.appendChild(figura);
                                contadorFiguras++;
                            });
                        }
                    }
                },
                error: function() {
                    alert('No se pudo cargar el elemento');
                }
            });
            document.querySelector('a-scene').setAttribute('torus-cleanup', '');
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
        AFRAME.registerComponent('contador-bloques', {
            schema: {
                minX: {
                    type: 'number',
                    default: -1.5
                },
                maxX: {
                    type: 'number',
                    default: 1.5
                },
                minZ: {
                    type: 'number',
                    default: -9.5
                },
                maxZ: {
                    type: 'number',
                    default: -6.5
                },
                minY: {
                    type: 'number',
                    default: 0.5
                }
            },

            init: function() {
                this.bloquesContados = new Set();
                this.puntos = 0;
            },

            tick: function() {
                const bloques = document.querySelectorAll('.bloque');
                let nuevosPuntos = 0;

                bloques.forEach(bloque => {
                    if (bloque.getAttribute('scored')) return;

                    const pos = new THREE.Vector3();
                    bloque.object3D.getWorldPosition(pos);

                    if (
                        pos.x >= this.data.minX && pos.x <= this.data.maxX &&
                        pos.z >= this.data.minZ && pos.z <= this.data.maxZ &&
                        pos.y >= this.data.minY
                    ) {
                        bloque.setAttribute('scored', true);
                        nuevosPuntos++;
                        console.log(nuevosPuntos);
                    }
                });

                if (nuevosPuntos > 0) {
                    this.puntos += nuevosPuntos;
                    document.querySelector('#puntos').setAttribute('value', `Puntos: ${this.puntos}`);
                    document.querySelector('#boton2').setAttribute('visible', 'false');
                }

                // Nivel superado con 10 bloques
                if (this.puntos >= 8) {

                    // Si quieres que el nivel 3 se muestre al final del nivel 2, descomenta la siguiente línea:
                    document.querySelector('#boton3').setAttribute('visible', 'true');

                }
            }
        });
    </script>

</body>

</html>