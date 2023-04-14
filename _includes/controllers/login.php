<?php
    require_once(__DIR__."/../utils/_init.php");
    require_once(__DIR__."/../models/user.php");
    $error = false;
    if(isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $remember = isset($_POST['remember']);
        if(User::login($username, $password, $remember)) {
            header("Location: ".PROJECT_URL."/search.php");
        } else {
            $error = "Invalid username or password";
        }
    }
    require_once(__DIR__."/../views/login.php");

