<?php
ini_set('display_errors',1);
date_default_timezone_set("Asia/Shanghai");
define("ROOT_PATH", str_replace("public","",__DIR__));
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
require_once ROOT_PATH . "/environment.php";
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . "/system/tr/init.php";
tr_init::getInstance()->create();