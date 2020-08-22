<?php

namespace App;

use App\Models\UserManager;
use App\Traits\UserPages;
use App\Traits\UserTasksBook;

class Controller extends BaseController
{
    use UserPages, UserTasksBook;

    /**
     *  Home page
     */
    function index()
    {
        if (!UserManager::getInstance()->isUserLogged()) {
            redirectTo('signin');
        }

        $this->load->view('index.php', array('rec' => $this->data));
    }
}