<?php
    function get_project_url() {
        return PROJECT_URL;
    }
    function get_db_host() {
        return DB_HOST;
    }
    function get_db_name() {
        return DB_NAME;
    }
    function get_db_user() {
        return DB_USER;
    }
    function get_db_pass() {
        return DB_PASS;
    }
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
    function checkalpha($var){
        return !empty($var) && ctype_alpha($var);
    }
    function m_checkalpha($value, $keys){
        $errors = array();
        foreach($keys as $key){
            if(!checkalpha($value[$key])){
                $errors[] = $key;
            }
        }
        return $errors;
    }
    function m_isset($value, $keys) {
        $errors = array();
        foreach ($keys as $key) {
            if (!isset($value[$key]) || empty($value[$key])) {
                $errors[] = $key;
            }
        }
        return $errors;
    }
    function check_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    function check_password($password) {
        return strlen($password) >= 6;
    }
    function check_username($username) {
        return strlen($username) >= 3;
    }
    function bool_to_str($bool) {
        return $bool ? "1" : "0";
    }
    function html($str) {
        return htmlspecialchars($str);
    }
    function check_price($price) {
        return preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $price);
    }
    function check_age($age) {
        return preg_match("/^[0-9]{1,2}$/", $age);
    }