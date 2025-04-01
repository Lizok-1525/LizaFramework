<div class="container mt-5 intro">
  <h2>Modificar Noticia</h2>
  <form method="post" action="modificar-<?= $id ?>">
    <div class=" mb-3">
      <label for="titulo" class="form-label">Título</label>
      <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="contenido" class="form-label">Contenido</label>
      <textarea class="form-control" id="contenido" name="contenido" rows="5" required><?= htmlspecialchars($noticia['contenido']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-secondary">Guardar Cambios</button>
    <a href="index" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
<?php include("../inc/navegacion.inc.php"); ?>