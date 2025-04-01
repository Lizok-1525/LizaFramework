<?php

$old_sessionid = session_id();

session_regenerate_id();

$new_sessionid = session_id();
//echo ' aaaaaaaaaaaaaaaaaaaa';

if (!isset($_SESSION["usuario"])) {
    session_regenerate_id(true);
    header('Location: ../login');
    exit;
}
