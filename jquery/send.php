<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>jQuery dinámico</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</head>

<body class="bg-body-secondary">

    <div class="container">

        <div class="row mh-80" id="mensajes" style="padding-bottom: 80px;">
        </div>
        <div class="input-group w-80  p-3 fixed-bottom">

            <input type="text" class="form-control" placeholder="Escribe tu mensaje..." name="mensaje" id="mensaje">
            <button id="enviar" class="btn btn-outline-secondary" type="submit" class="btn btn-outline-secondary">Enviar</button>
        </div>

    </div>


    <script>
        function cargarMensajes() {
            $("#mensajes").load("mensajes.php");
        }

        setInterval(cargarMensajes, 8000); // actualiza cada 8 segundos
        cargarMensajes();


        $('#enviar').click(function() {
            var mensaje = $('#mensaje').val();

            $.post("mensajes.php", {
                mensaje: mensaje
            }, function() {
                cargarMensajes(); // recarga después de enviar
                $('#mensaje').val('');
            }).fail(function(error) {
                console.error("Error al enviar mensaje:", error);
            });
        });


        $('#mensaje').keypress(function(event) {
            if (event.which === 13) { // 13 es el código de la tecla Enter
                event.preventDefault(); // evita que el formulario recargue la página
                $('#enviar').click(); // dispara el click en el botón enviar
            }
        });
    </script>
    <script type="module">
        // Import the functions you need from the SDKs you need
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import {
            getAnalytics
        } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-analytics.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyCis0PJOi20N8YcN87qY5KRUyWyFLjE9M4",
            authDomain: "mi-pagina-web-46841.firebaseapp.com",
            projectId: "mi-pagina-web-46841",
            storageBucket: "mi-pagina-web-46841.firebasestorage.app",
            messagingSenderId: "671932914524",
            appId: "1:671932914524:web:d482e9700874d08ec2bf99",
            measurementId: "G-LZ3Y55P7QD"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
    </script>
</body>


</html>