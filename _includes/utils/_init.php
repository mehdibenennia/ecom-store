<?php
    ob_start("ob_gzhandler");
    session_start();
    require_once(__DIR__ . "/../config.php");
    require_once(__DIR__ . "/utils.php");
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
?>