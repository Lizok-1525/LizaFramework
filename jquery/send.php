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

        <div class="row" id="mensajes">

        </div>

        <div class="input-group w-80  p-3 fixed-bottom">

            <input type="text" class="form-control" placeholder="Escribe tu mensaje..." name="mensaje" id="mensaje">
            <button id="enviar" class="btn btn-outline-secondary" type="submit" class="btn btn-outline-secondary">Enviar</button>
        </div>

    </div>
    <script>

    </script>
    <script>
        $('#enviar').click(function() {
            var mensaje = $('#mensaje').val();

            $.ajax({
                url: 'mensajes.php',
                type: 'POST',

                data: {
                    mensaje: mensaje
                },
                success: function(respuesta) {
                    //console.log('Respuesta del servidor:', respuesta);
                    $("#mensajes").load("mensajes.php"); // recarga los mensajes
                    $('#mensaje').val('');
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
            $("#mensajes").load("mensajes.php");
        });

        $('#mensaje').keypress(function(event) {
            if (event.which === 13) { // 13 es el código de la tecla Enter
                event.preventDefault(); // evita que el formulario recargue la página
                $('#enviar').click(); // dispara el click en el botón enviar
            }
        });
    </script>

</body>


</html>