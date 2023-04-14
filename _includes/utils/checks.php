<?php
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
    function is_admin() {
        return isset($_SESSION['user_id']) && $_SESSION['is_admin'];
    }
    function is_logged_in_as($user_id) {
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id;
    }
    function is_logged_in_as_admin() {
        return isset($_SESSION['user_id']) && $_SESSION['is_admin'];
    }
    
