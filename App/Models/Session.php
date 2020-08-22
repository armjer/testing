<?php

namespace App\Models;

class Session
{
    /**
     *
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        @session_start();
    }

    /**
     *
     * Get variable from session
     *
     * @param string $key key of the variable
     * @return mixed  the value of the variable
     */
    public function get($key)
    {
        return @$_SESSION[$key];
    }

    /**
     *
     * Store variable in session
     *
     * @param string $key key of the variable
     * @param string $value value of the variable
     * @return none
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     *
     * Destroy session
     *
     * @return none
     */
    public function destroy()
    {
        $_SESSION = array();
        session_destroy();
    }
}