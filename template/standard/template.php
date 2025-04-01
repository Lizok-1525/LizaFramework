<!DOCTYPE html>
<html lang="es">

<head>
    <title> <?= $head_title ?> </title>
    <meta name="description" content="Noticias de Yelyzaveta Krasnolutska">
    <link rel="canonical" href="<?= $canonical_name  ?>" />
    <?php include(BASE_PATH . "/template/standard/metas.inc.php"); ?>
</head>

<body class="bg-body-secondary">
    <?php include(BASE_PATH . "/template/standard/head.inc.php"); ?>
    <main class="container content ">
        <?= $content ?>
    </main>

    <?php include(BASE_PATH . "/template/standard/foot.inc.php"); ?>
</body>

</html>