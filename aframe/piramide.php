<html>

<head>
    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
    <script src="https://unpkg.com/aframe-extras@6.1.1/dist/aframe-extras.min.js"></script>
    <script src="https://unpkg.com/aframe-super-hands-component@4.0.5/dist/aframe-super-hands.min.js"></script>
    <script src="https://unpkg.com/aframe-physics-system@4.0.1/dist/aframe-physics-system.min.js"></script>


</head>

<body>
    <a-scene physics="gravity: -9.8" cursor="rayOrigin: mouse">
        <!-- Suelo -->
        <a-plane position="0 0 -4" rotation="-90 0 0" width="10" height="10" color="#7BC8A4" static-body></a-plane>

        <a-entity position="0 1.6 0">
            <a-camera></a-camera>
            <a-entity
                laser-controls="hand: right"
                raycaster="objects: .grabbable"
                super-hands></a-entity>
        </a-entity>

        <!-- Palo central -->
        <a-cylinder id="palo" position="0 1 -4" radius="0.1" height="2" color="#333" static-body></a-cylinder>


        <!-- Botón para añadir piezas -->
        <a-box id="boton" position="0 0.2 -2" depth="0.2" height="0.2" width="1" color="#F00"
            class="clickable"
            text="value: Añadir piezas; align: center; color: white; width: 4">
        </a-box>

        <!-- Cámara -->

        <a-entity><a-cursor color="black" super-hands></a-cursor></a-entity>



        <script>
            let contador = 0;

            document.querySelector('#boton').addEventListener('click', () => {
                const scene = document.querySelector('a-scene');

                // Creamos dos donuts, uno a la izquierda y otro a la derecha del palo
                const posiciones = [-1, 1]; // x: izquierda y derecha

                for (let i = 0; i < 2; i++) {
                    const torus = document.createElement('a-torus');

                    const x = posiciones[i]; // -1 o 1
                    const z = -4; // misma z que el palo
                    const y = 0.5;

                    torus.setAttribute('position', `${x} ${y} ${z}`);
                    torus.setAttribute('rotation', '90 0 0'); // ahora está de pie
                    torus.setAttribute('radius', 0.3);
                    torus.setAttribute('radius-tubular', 0.1);
                    torus.setAttribute('segments-radial', 16);
                    torus.setAttribute('segments-tubular', 24);
                    torus.setAttribute('color', '#' + Math.floor(Math.random() * 16777215).toString(16));
                    torus.setAttribute('class', 'grabbable');
                    torus.setAttribute('dynamic-body', 'mass: 0.5');
                    torus.setAttribute('grabbable', '');

                    scene.appendChild(torus);
                    contador++;
                }
            });
        </script>
    </a-scene>
</body>

</html>