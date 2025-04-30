<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A-Frame Scene with Dynamic Elements</title>

    <!-- Scripts -->
    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
    <script
        src="https://unpkg.com/aframe-environment-component@1.3.x/dist/aframe-environment-component.min.js"></script>
    <style>
        #botones {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }

        button {
            display: block;
            margin: 5px;
        }
    </style>
</head>

<body>
    <div id="botones">
        <button onclick="cargarModelo(1)">Cargar Coche</button>
        <button onclick="cargarModelo(2)">Cargar Arbol</button>
        <button onclick="cargarModelo(3)">Cargar Pato</button>
        <hr>
        <button onclick="cargarTodos()">Cargar Todos</button>
    </div>

    <!-- Escena de A-Frame -->
    <a-scene id="scene">


        <a-entity camera look-controls position="0 1.6 0" wasd="true" wasd-controls="acceleration: 100">
            <a-cursor color="#FAFAFA"></a-cursor>
        </a-entity>

        <a-light type="ambient" intensity="1"></a-light>
        <!-- Contenedor donde se añadirán modelos -->
        <a-entity id="model-container"></a-entity>

        <a-entity environment="preset: egypt; ground: hills; shadow: true"></a-entity>
    </a-scene>


    <script>
        function cargarModelo(id) {
            fetch('get-model.php?ID=' + id)
                .then(res => res.json())
                .then(data => {
                    const contenedor = document.getElementById('model-container');


                    const entidad = document.createElement('a-entity');
                    entidad.setAttribute('gltf-model', data.url);
                    entidad.setAttribute('position', data.posicion || '0 1.6 -3');
                    entidad.addEventListener('click', function() {
                        contenedor.removeChild(this);
                    });
                    contenedor.appendChild(entidad);
                })
                .catch(err => console.error("Error cargando modelo:", err));
        }

        function cargarTodos() {
            fetch('get-modelos.php')
                .then(res => res.json())
                .then(data => {
                    const contenedor = document.getElementById('model-container');

                    let offsetX = -2; // separarlos horizontalmente

                    data.forEach(modelo => {
                        const entidad = document.createElement('a-entity');
                        entidad.setAttribute('gltf-model', modelo.url);
                        entidad.setAttribute('position', modelo.posicion || `${offsetX} 1.6 -3`);

                        entidad.addEventListener('click', function() {
                            contenedor.removeChild(this);
                        });

                        contenedor.appendChild(entidad);
                        offsetX += 2; // siguiente modelo más a la derecha
                    });
                })
                .catch(err => console.error("Error cargando modelos:", err));
        }
    </script>

</body>

</html>