AFRAME.registerComponent('mouse-grab', {
    init: function () {
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


$('.load-level').click(function () {
    const type = $(this).data('type');

    $.ajax({
        url: 'game.php',
        type: 'GET',
        data: {
            type: type
        },
        success: function (data) {

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
                        torus.setAttribute('class', 'donut grabbable aro');
                        torus.setAttribute('ammo-body', 'type: dynamic; disableDeactivation: false; linearDamping: 0.1; angularDamping: 0.1; ');
                        torus.setAttribute('ammo-shape', 'type: hull');
                        torus.setAttribute('shadow', '');
                        scene.appendChild(torus);
                        contador++;
                    
                });
                $('#content').append(data);



                //------------------------------------------------------------

                AFRAME.registerComponent('torus-cleanup', {
                    tick: function () {
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
        error: function () {
            alert('No se pudo cargar el elemento');
        }
    });
    document.querySelector('a-scene').setAttribute('torus-cleanup', '');
});

   AFRAME.registerComponent('rotacion-crane', {
      init: function() {
        this.rotationSpeed = 5; // Grados por evento
        window.addEventListener('keydown', this.onKeyDown.bind(this));
      },


      onKeyDown: function(event) {
       
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
        else if (event.key.toUpperCase() === 'K') {
          currentRotations.y += this.rotationSpeed;
          craneArm.setAttribute('rotation', currentRotations);
        }
        else if (event.key.toUpperCase() === 'Ñ') {
          currentRotations.y += this.rotationSpeed;
          craneArm.setAttribute('rotation', currentRotations);
        }
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

    init: function () {
        this.score = 0;
    },

    tick: function () {
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

    init: function () {
        this.bloquesContados = new Set();
        this.puntos = 0;
    },

    tick: function () {
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


        /*      window.addEventListener('load', () => {
             const donut = document.querySelector('.donut');

             // Establecer la posición del donut
             donut.setAttribute('position', {
               x: 0,
               y: 2.4,
               z: -2.3
             });

             pegarDonutADraga(); // ahora sí, seguro

             jumpDonut(donut); // Llama a la función de salto aquí

           });*/


        // -----------------------------------------------------------
        /*
        document.querySelector('.spawn-rings').addEventListener('click', () => {

          const scene = document.querySelector('a-scene');
          if (!document.querySelector('#donut')) {
            const donut = document.createElement('a-torus');
            donut.setAttribute('id', 'donut');
            donut.setAttribute('rotation', '90 0 0');
            donut.setAttribute('radius', '0.3');
            donut.setAttribute('radius-tubular', 0.05);
            donut.setAttribute('segments-radial', 8);
            donut.setAttribute('segments-tubular', 12);
            donut.setAttribute('color', '#' + Math.floor(Math.random() * 16777215).toString(16));
            donut.setAttribute('class', 'grabbable aro');
            donut.setAttribute('ammo-body', 'type: dynamic; disableDeactivation: false; linearDamping: 0.1; angularDamping: 0.1; mass: 1;');
            donut.setAttribute('ammo-shape', 'type: hull');
            donut.setAttribute('position', {
              x: 0,
              y: 2.4,
              z: -2.3
            });

            // Llama a la función de salto aquí

          }
          scene.appendChild(donut);

          pegarDonutADraga(); // ahora sí, seguro

          jumpDonut(donut);
        });*/

        // -----------------------------------------------------------
        /*    AFRAME.registerComponent('jump-on-space', {
              init: function() {
                const el = this.el;

                el.addEventListener('body-loaded', () => {
                  //console.log('[jump-on-space] Body loaded, ready to jump');

                  window.addEventListener('keydown', function(event) {
                    
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
                    }
                  });
                });
              }
            });
        */
        // -----------------------------------------------------------