<?php

namespace App\Traits;

use App\Models\Database;
use App\Models\Forsafty;
use App\Models\Localization;
use App\Models\UserManager;

trait UserPages
{
    function signin()
    {
        $userMgr = UserManager::getInstance();

        if ($userMgr->loggedIn()) {
            redirectTo('index');
        } //already logged in

        $error = '';
        if (isset($_POST['username'], $_POST['password'])) {
            if ($userMgr->authenticate($_POST['username'], $_POST['password'])) {
                redirectTo('profile');
            }

            $error = 'The username or password you have entered is incorrect.';
        }

        $this->load->view('signin.php', array('rec' => $this->data, 'error' => $error));
    }

    function logout()
    {
        UserManager::getInstance()->logout();
        redirectTo('signin');
    }

    function signup()
    {
        $userMgr = UserManager::getInstance();
        if($userMgr->isUserLogged()) {
            redirectTo('signin');
        }

        $error =  $invalidEmail = '';
        $localization = Localization::getInstance();
        require('./locale/' . $localization->getCurrentLanguage() . '.php');

        if (count($_POST)) {
            $res = $userMgr->saveUser($_POST);
            if ($res == 0 && $userMgr->authenticate($_POST['email'], $_POST['password'])) {
                redirectTo('profile');
            }

            if ($res == 3) {
                $invalidEmail = _t('validation_email_taken');
                $error = _t('registration_failed').' <br>'.$invalidEmail;
            } else {
                $error = _t('registration_failed');
            }
        }


        $this->load->view('signup.php', array('rec' => $this->data, 'invalidEmail' => $invalidEmail, 'error' => $error));
    }

    function profile()
    {
        $userMgr = UserManager::getInstance();
        if(!$userMgr->isUserLogged()) {
            redirectTo('signin');
        }

        $localization = Localization::getInstance();
        require('./locale/' . $localization->getCurrentLanguage() . '.php');

        $stext = new Forsafty();
        $user =  $userMgr->getCurrentUser();
        $pdo = Database::getInstance();
        $userId = $user->getId();
        $birthdate = date("Y-m-d", strtotime($user->getBirthdate()));
        $age = $user->getAge($birthdate);

        if (count($_POST)) {
            $email = (isset($_POST['email']) && trim($_POST['email'])) ? trim($_POST['email']) : '';
            $name = (isset($_POST['name']) && trim($_POST['name'])) ? trim($_POST['name']) : '';
            if ($name && $email) {
                $query = "UPDATE `user` set `email`=?, `firstname`=?  where id=?";
                $st = $pdo->prepare($query);
                $st->execute(array($email, $name, $userId));

                redirectTo('index');
            } else {
                echo 'Не заполнены объязательные поля';
            }
        }

        $this->load->view(
            'profile.php',
            array('rec' => $this->data, 'stext' => $stext, 'user' => $user, 'age' => $age, 'birthdate' => $birthdate)
        );
    }
}