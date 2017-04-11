<?php
ob_start();
session_start();
//Configuration file
define("SITE_NAME","Martikco");

//Constants
define("CLASSES_DIR", 'controller');
define("MODELS_DIR", 'model');
define("VIEW_DIR", 'View');
define("DS", '/');
//posts per page
define("POST_PER_PAGE",10);

//autoload
spl_autoload_register(function ($class_name) {
    if(file_exists(CLASSES_DIR.DS.$class_name . '.php')){
        include CLASSES_DIR.DS.$class_name . '.php';
    }
    if(file_exists(MODELS_DIR.DS.$class_name . '.php')){
        include MODELS_DIR.DS.$class_name . '.php';
    }
});

//DB
$host = 'localhost';
$db   = 'urias_blog';
$charset = 'utf8';
define("DB_DSN", "mysql:host=$host;dbname=$db;charset=$charset");
define("DB_USER", 'martikco');
define("DB_PASS", 'martikcodev');
