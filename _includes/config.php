<?php
    define('PROJECT_URL', getenv('MINI_URL')?:'/');
    define('PROJECT_PWD', getenv('MINI_PWD')?:'password');
    define('PROJECT_SALT', getenv('MINI_SALT')?:'salt');
    define('PROJECT_COOKIE_NAME', getenv('MINI_COOKIE')?:'cookie');
    define('PROJECT_COOKIE_TIME', intval(getenv('MINI_COOKIE_TIME')?:'2592000'));
    define('DB_HOST', getenv('MINI_DB_HOST')?:'mysql');
    define('DB_NAME', getenv('MINI_DB_NAME')?:'mini');
    define('DB_USER', getenv('MINI_DB_USER')?:'root');
    define('DB_PASS', getenv('MINI_DB_PASS')?:'');