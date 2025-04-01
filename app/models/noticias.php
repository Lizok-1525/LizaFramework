<?php

header("Cache-Control: no-cache, must-revalidate");


$head_title = "Noticias";
$head_description = "Noticias de Yelyzaveta Krasnolutska";
$canonical_name = "https://liza.ma-no.es/noticias";

if (!$core) {
    $core = new core();
}
