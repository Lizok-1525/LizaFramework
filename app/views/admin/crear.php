    <div class="container content mt-5 intro">
      <h2 class="mb-4">Crear Noticia</h2>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Título</label>
          <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Contenido</label>
          <textarea name="contenido" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-secondary">Guardar</button>
      </form>

    </div>
    <?php include("../inc/navegacion.inc.php"); ?>