<?php

namespace App\Models;

/**
 * удаление тегов
 * нужные теги можно добавлять через setWhiteList
 */
class Forsafty
{
    protected $_str = null;
    protected $_Text = null;
    protected $_whitelist = '';

    function __construct()
    {
        /*  разрешенные теги   */
        $this->_whitelist = '<p><a><b><br /><br/><br><div></div><input><form><img><textarea><li><ol><ul><table><tr><td><div><iframe><div></div>';

    }

    public function setString($str)
    {
        $this->_str = $str;
    }

    public function getString()
    {
        return $this->_str;
    }


    public function setWhiteList($str)
    {
        $this->_whitelist = $str;
    }

    public function getWhiteList($str)
    {
        return $this->_whitelist;
    }

    /*  удаление лишных тегов   */
    public function convert($text)
    {
        $this->Text = $text;

        if (trim($this->Text) != '') {
            $this->Text = strip_tags($this->Text, $this->_whitelist);
        }

        return $this->Text;
    }
}