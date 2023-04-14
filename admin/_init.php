<?php
    require_once(__DIR__."/../_includes/utils/_init.php");
    require_once(__DIR__."/../_includes/models/user.php");
    if(!isset($current_user) || !$current_user || !$current_user->isAdmin()) {
        header("Location: ".PROJECT_URL."/");
        exit();
    }
