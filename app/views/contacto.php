<div class="container content">
  <div class="row mt-3 intro">
    <form class="my-form" action="sendmail" method="post">
      <input type="hidden" id="csfr" name="csfr" value="<?= $CSFR ?>">
      <div class="row">
        <div class="col">
          <label for="name" class="form-label">Nombre y Apellido</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="John Miklay" required>
        </div>
        <div class="col">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <label for="number" class="form-label">Telefono</label>
          <input type="phone" class="form-control" id="number" name="number" placeholder="+341122334455">
        </div>
        <div class="col">
          <label for="mensaje" class="form-label">Tu mensaje</label>
          <textarea class="form-control" id="mensaje" name="mensaje" rows="5"></textarea>
        </div>
      </div>
      <div class="row m-3 p-2">
        <input type="submit" class="btn btn-outline-secondary" value="Enviar">
      </div>
    </form>

  </div>
  <?php include(BASE_PATH . "/inc/navegacion.inc.php"); ?>
</div>