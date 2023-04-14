<?php
    if(!$current_user && ($title != "Login" && $title != "Register")) {
        header("Location: ".PROJECT_URL."/");
    }else if($current_user && ($title == "Login" || $title == "Register")) {
        header("Location: ".PROJECT_URL."/search.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <link rel="stylesheet" href="<?=PROJECT_URL?>/css/style.css">
    <script src="<?=PROJECT_URL?>/js/alpine-mask.min.js" defer></script>
    <script src="<?=PROJECT_URL?>/js/alpine.min.js" defer></script>
</head>
