<div class="row intro">
  <div class="col-8">
    <div class="login">
      <h1>Sistema de Login Básico</h1>
      <p>Has entrado como <?php echo ($_SESSION["usuario"]) ?></p>
    </div>
    <h2>Información del Usuario</h2>
    <div>
      <p>
        La siguiente es la información registrada de tu cuenta
      </p>
      <table>
        <tr>
          <td>
            <p>Usuario: <?php echo ($_SESSION['usuario']) ?></p>
          </td>
        </tr>
        <tr>
          <td>
            <p>Email: <?php echo ("$email") ?></p>
          </td>
        </tr>
      </table>
    </div>
    <div class="row mt-2 ">
      <div class="col">
        <a href="crear" class="nav-item m-1 p-2 custom-link btn btn-secondary">Crear noticia nueva</a>

        <form method="post">
          <button type="submit" class="btn btn-outline-secondary">Generar claves</button>
        </form>

        <?php if (!empty($private_key_api) && !empty($public_key_api)) { ?>
          <p class="m-2"><strong>Clave Privada: </strong><input type="checkbox" id="mostrar" checked onclick="toggleTexto()">
          <p id="texto" class="m-2"> <?php echo htmlspecialchars($private_key_api); ?></p>
          </p>
          <p class="m-2"><strong>Clave Pública: </strong> <?php echo htmlspecialchars($public_key_api); ?> </p>
        <?php } ?>
      </div>

      <div class="row mt-3">
        <h2>Noticias Recientes</h2>
        <ul class="list-group">
          <?php
          $result = $conn->query("SELECT * FROM noticias ORDER BY ID DESC");
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $contenidoVistaPrevia = strip_tags($core->convertirTextoAHTML($row['contenido'])); // Elimina etiquetas HTML
          ?>
              <li class="list-group-item">
                <h5><?= $row['titulo'] ?></h5>
                <p><?= substr($contenidoVistaPrevia, 0, 150); ?>...</p>

                <a href="modificar-<?= $row['ID'] ?>" class=" m-1 p-2  btn btn-outline-success">Modificar</a>
                <a href="borrar-<?= $row['ID'] ?>" class=" m-1 p-2  btn btn-outline-danger">Borrar</a>
                <a href="../noticias/<?= $core->generarSlug($row['titulo']) ?>-<?= $row['ID'] ?>" class="m-1 p-2  btn btn-outline-secondary">Ver</a>
              </li>
          <?php
            }
          } else {
            echo "<p>No hay noticias disponibles</p>";
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-4">
    <a href="cerrar_sesion" class="btn btn-outline-secondary">Cerrar Sesion</a>
    <a href="../testeo" class="btn btn-outline-secondary">Acceder a base guardada</a>
    <a href="../test" class="btn btn-outline-secondary">Acceder a calculadora</a>
  </div>



  <!-- Texto oculto
  <p id="texto">Este es el texto que se mostrará o ocultará.</p> -->

  <script>
    function toggleTexto() {
      var texto = document.getElementById("texto");
      var checkbox = document.getElementById("mostrar");

      if (checkbox.checked) {
        texto.style.display = "block"; // Mostrar el texto
      } else {
        texto.style.display = "none"; // Ocultar el texto
      }
    }
  </script>
</div>