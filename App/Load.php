<?php

namespace App;

/**
 * Class Load
 * @package App
 */
class Load
{
    /**
     * @param $file_name
     * @param null $data
     */
    function view($file_name, $data = null)
    {
        if (is_array($data)) {
            // преобразуем элементы массива в переменные
            extract($data);
        }

        // динамически подключаем шаблон отображения (вид)
        require_once APP_PATH . 'views/' . $file_name;
    }
}