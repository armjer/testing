<?php

namespace App\Models;

class Localization
{
    private static $instance = null;
    private $session;
    private $currentLanguage;
    private $allLanguages = array();

    /**
     *
     * Constructor
     *
     * @return void
     */
    private function __construct()
    {

        $this->session = new Session();

        global $SYSTEM_LANGUAGES;
        $SYSTEM_LANGUAGES = array('en', 'ru');
        $this->setAllLanguages($SYSTEM_LANGUAGES);

        $language = $this->session->get('current_language');
        if (!$language) $language = DEFAULT_LANGUAGE;
        $this->setCurrentLanguage($language);
    }

    /**
     *
     * Clone
     *
     * @return none
     */
    private function __clone()
    {
    }

    /**
     * Get singleton instance
     *
     * @return Localization|null
     */
    public static function getInstance()
    {
        return (self::$instance ? self::$instance : self::$instance = new self());
    }

    public function setAllLanguages($langs)
    {
        $this->allLanguages = $langs;
    }

    public function getAllLanguages()
    {
        return $this->allLanguages;
    }

    public function setCurrentLanguage($lang)
    {
        if (!in_array($lang, $this->allLanguages)) return;
        $this->session->set('current_language', $lang);
        $this->currentLanguage = $lang;
    }

    public function getCurrentLanguage()
    {
        return $this->currentLanguage;
    }
}