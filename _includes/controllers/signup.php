<?php
require_once(__DIR__ . "/../utils/_init.php");
require_once(__DIR__ . "/../models/user.php");
$error = "";
if (isset($_POST['submit'])) {
    if (count($fields_error = m_isset($_POST, ['username', 'password', 'password2', 'email', 'first_name', 'last_name'])) > 0) {
        $error = implode(" & ", $fields_error) . ' are required';
    } else if (count($alpha_error = m_checkalpha($_POST, ['first_name', 'last_name'])) > 0) {
        $error = implode(" & ", $alpha_error) . ' must be alphabetic';
    } else if (!check_email($_POST['email'])) {
        $error = "Invalid email";
    } else if (!check_password($_POST['password'])) {
        $error = "Password must be at least 6 characters";
    } else if ($_POST['password'] != $_POST['password2']) {
        $error = "Passwords do not match";
    } else if (!check_username($_POST['username'])) {
        $error = "Username must be at least 3 characters";
    } else {
        $user = User::find_by_username($_POST['username']);
        if ($user) {
            $error = "Username already exists";
        } else {
            $user = User::newUser(
                $_POST['username'],
                $_POST['password'],
                $_POST['email'],
                $_POST['first_name'],
                $_POST['last_name']
            );
            if ($user) {
                $user->login($_POST['username'], $_POST['password']);
                header("Location: " . PROJECT_URL . "/search.php");
            } else {
                $error = "Error creating user";
            }
        }
    }
}
require_once(__DIR__ . "/../views/signup.php");
