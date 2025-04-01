<div class="container">

  <div class="row intro">
    <div class="col">

      <h2>Datos del correo enviado</h2>
      <?php echo ($_POST['name']) ?><br>
      <?php echo ($_POST['email']) ?><br>
      <?php echo ($_POST['mensaje']) ?><br>
      <?php
      $sql = "INSERT INTO `emails` (`name`, `email`, `number`) VALUES ('" . $_POST['name'] . "', '" . $_POST['email'] . "', '" . $_POST['number'] . "');";

      if ($conn->query($sql) === TRUE) {
        echo " New record created successfully ";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();

      ############################################

      $message = "te ha escrito " . $_POST['name'] . " " . $_POST['email'] . " " . $_POST['mensaje'];

      mail('liza@ma-no.org', 'My Subject', $message);
      ?>
    </div>

  </div>
  <?php include(BASE_PATH . "/inc/navegacion.inc.php"); ?>
</div>