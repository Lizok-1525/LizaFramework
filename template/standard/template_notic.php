<!DOCTYPE html>
<html lang="es">

<head>
    <title> Blog de noticias </title>
    <meta name="description" content="<?= $head_description ?>">
    <?php include(BASE_PATH . "/inc/metas.inc.php");
    ?>
</head>

<body class="bg-body-secondary">
    <?php include(BASE_PATH . "/inc/head.inc.php");
    ?>
    <main class="container content ">
        <?php include(BASE_PATH . "/app/views/interface_json.php");
        ?>

    </main>

    <?php include(BASE_PATH . "/inc/foot.inc.php"); ?>
</body>

</html>