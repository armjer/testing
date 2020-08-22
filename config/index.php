<?php

// отображение сообщений об ошибках
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

define('APP_URL', './');
define('APP_PATH', dirname(__FILE__) . '/../');
define('LOCALE_PATH', APP_URL . 'locale/');
define('UPLOADS_PATH', APP_PATH . 'public/uploads/');
define('UPLOADS_URL', APP_URL . 'public/uploads/');
define('TBL_USER', 'user');

define('DEFAULT_LANGUAGE', 'en');

$SYSTEM_LANGUAGES = array('en', 'ru');
$LOCALE = array();

define('LOGIN', 'root');
define('PASSWD', 'admin');
define('MYSQL_DB', 'test_book_of_tasks');
define('HOST', 'localhost');

require_once 'autoloader.php';