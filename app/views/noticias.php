<div class="container content ">
  <div class="row intro">
    <div class="col-8">
      <div class="container mt-3">
        <h2>Noticias Recientes</h2>



        <?php



        $result = $conn->query("SELECT * FROM noticias ORDER BY id DESC");
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $contenidoVistaPrevia = strip_tags($core->convertirTextoAHTML($row['contenido'])); // Elimina etiquetas HTML

        ?>
            <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
              <div class="col p-4 d-flex flex-column position-static">
                <h3 class="mb-0"><?= $row['titulo'] ?></h3>
                <p class="card-text mb-auto"><?= substr($contenidoVistaPrevia, 0, 150); ?>...</p>
                <a href="noticias/<?= $core->generarSlug($row['titulo']) ?>-<?= $row['ID'] ?>" class="btn btn-secondary icon-link gap-1 icon-link-hover stretched-link ">Leer más <svg class="bi">
                    <use xlink:href="#chevron-right"></use>
                  </svg>
                </a>
              </div>
              <div class="col-auto d-none d-lg-block">
                <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                  <title><?= $row['titulo'] ?></title>
                  <image href="../../assets/images/logo-min.webp" width="100%" height="100%" />
                </svg>
              </div>
            </div>
        <?php
          }
        } else {
          echo "<p>No hay noticias disponibles</p>";
        }
        ?>
      </div>
    </div> <!-- Cierra el div.col -->
    <div class="col-2">
    </div>
  </div> <!-- Cierra el div.row -->
  <?php include(BASE_PATH . "/inc/navegacion.inc.php"); ?>
</div> <!-- Cierra el div.container -->