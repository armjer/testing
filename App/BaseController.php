<?php

namespace App;

use App\Models\Localization;

class BaseController
{
    public $load;
    public $model;
    public $db;
    public $data = [];
    public $error = [];

    function __construct()
    {
        $this->load = new Load();

        if (is_file(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.txt')) {
            $file = file_get_contents(
                __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.txt',
                FILE_USE_INCLUDE_PATH
            );
            $config = explode("\n", $file);
            $this->ip = trim(str_replace('ip:', '', $config[0]));
            $this->port = trim(str_replace('port:', '', $config[1]));
        } else {
            throw new \Exception("The config file does not exist");
        }

        /*  выбор страницы   */
        if (isset($_REQUEST['action']) && $_REQUEST['action']) {
            return $this->__call($_REQUEST['action'], array());
        } else {
            return $this->index();
        }
    }

    /*  подключаем классы   */

    function __autoload($class_name)
    {
        include $class_name . '.php';
    }

    /*  для динамического подключения страниц  */

    public function __call($name, $params)
    {
        if (true == method_exists($this, $name)) {
            call_user_func_array(array(&$this, $name), $params);
        }
    }

    function locale()
    {
        if (isset($_GET['language'])) {
            $language = $_GET['language'];
            $page = $_GET['page'];
            $localization = Localization::getInstance();
            $localization->setCurrentLanguage($language);
            redirectTo($page);
        }
    }

    function index()
    {

    }
}