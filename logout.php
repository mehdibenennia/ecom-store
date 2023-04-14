<?php
    require_once(__DIR__."/_includes/utils/_init.php");
    require_once(__DIR__."/_includes/models/user.php");
    User::logout();
    header("Location: ".PROJECT_URL."/");