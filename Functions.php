<?php

use App\Models\Localization;

$file = __DIR__ . DIRECTORY_SEPARATOR . 'locale/' . Localization::getInstance()->getCurrentLanguage() . '.php';

if (is_file($file)) {
    require_once $file;
}

function url($comp)
{
    return APP_URL . $comp;
}

function redirectTo($url)
{
    $url = '?action=' . $url;
    header("Location: $url");
    exit;
}

function refresh()
{
    header("Refresh:0");
    exit;
}

function ageFromDate($date)
{
    $birthDate = explode("-", $date);
    return (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md") ? ((date("Y") - $birthDate[0]) - 1) : (date("Y") - $birthDate[0]));
}

function _t($str)
{
    global $LOCALE;

    return isset($LOCALE[$str]) ? $LOCALE[$str] : '__' . $str . '__';
}