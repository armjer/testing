<?php

namespace App\Models;

use PDO;

/**
 * UserManager
 */
class UserManager
{
    private static $instance = null;
    private $session;
    private $currentUser;

    /**
     * Constructor
     *
     * @return void
     */
    private function __construct()
    {
        $this->session = new Session();
        $this->currentUser = new CUser();

        if ($this->session->get('currentUser')) {
            $this->currentUser = $this->getUser($this->session->get('currentUser'));
        }
    }

    /**
     * Clone
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Get singleton instance
     *
     * @return UserManager|null
     */
    public static function getInstance()
    {
        return (self::$instance ?: self::$instance = new self());
    }

    /**
     * Get current user
     *
     * @return object  user
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Check if email is already taken
     *
     * @return boolean
     */
    public function emailTaken($email)
    {
        $pdo = Database::getInstance();
        $st = $pdo->prepare("SELECT * FROM " . CUser::TABLE . " WHERE email=? LIMIT 1");
        $st->execute(array($email));
        if ($st->rowCount() == 1) return true;

        return false;
    }

    public function userFromArray($data)
    {
        $user = new CUser();
        foreach ($data as $key => $val) {
            $func = 'set' . ucfirst($key);
            $user->$func($val);
        }
        return $user;
    }

    /**
     * Authenticate a user
     *
     * @param string $username email|username
     * @param string $pass password
     * @return object/false  user in case of success
     */
    public function authenticate($username, $pass)
    {
        if (!$username || !$pass) return false;

        $pass = sha1($pass);

        $pdo = Database::getInstance();
        $st = $pdo->prepare("SELECT * FROM " . CUser::TABLE . " WHERE `email`=? OR `username`=? LIMIT 1");
        $st->execute(array($username, $username));

        if ($st->rowCount() != 1) return false;

        $user = $st->fetch(PDO::FETCH_ASSOC);

        if ($user['password'] == $pass) {
            $user = $this->userFromArray($user);
            $this->currentUser = $user;

            //store user id in session
            $this->session->set('currentUser', $this->currentUser->getId());

            return $user;
        }

        return false;
    }

    /**
     * Logout current user and destroy session
     *
     * @return void
     */
    public function logout()
    {
        $this->session->destroy();
        $this->currentUser = new CUser();
    }

    /**
     * Check if the user is authenticated
     *
     * @return boolean
     */
    public function loggedIn()
    {
        return ($this->currentUser->getId() ? true : false);
    }

    /**
     * Save user information in database
     *
     * @param object $post all post data
     * @return boolean
     */
    public function saveUser($post)
    {
        $email = $post['email'];
        $username = $post['username'];
        $password = $post['password'];
        $confirmPassword = $post['confirmpassword'];
        $firstname = $post['firstname'];
        $lastname = $post['lastname'];
        $gender = $post['gender'];
        $birthdayYear = $post['birthday_year'];
        $birthdayMonth = $post['birthday_month'];
        $birthdayDay = $post['birthday_day'];

        if (!$email || !$username || !$password || !$confirmPassword || !$firstname || !$lastname || !$gender || !$birthdayYear || !$birthdayMonth || !$birthdayDay) return 1;
        if ($password != $confirmPassword) return 2;
        if ($this->emailTaken($email)) return 3;
        if (strlen($username) < 3 || strlen($username) > 255) return 4;

        $password = sha1($password);
        $birthdate = sprintf("%d-%02d-%02d", intval($birthdayYear), intval($birthdayMonth), intval($birthdayDay));
        $profilePic = $this->uploadProfilePic();
        if (!$profilePic) $profilePic = '';

        $pdo = Database::getInstance();
        $query = "INSERT INTO " . CUser::TABLE . " (email, username, password, firstname, lastname, gender, birthdate, picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $st = $pdo->prepare($query);
        if ($st->execute(array($email, $username, $password, $firstname, $lastname, $gender, $birthdate, $profilePic))) {
            return 0;
        }

        return 4;
    }

    /**
     * @param $post
     * @return int
     */
    public function saveMessage($post)
    {
        $email = $post['email'];
        $password = $post['password'];
        $confirmPassword = $post['confirmpassword'];
        $firstname = $post['firstname'];
        $lastname = $post['lastname'];
        $gender = $post['gender'];
        $birthdayYear = $post['birthday_year'];
        $birthdayMonth = $post['birthday_month'];
        $birthdayDay = $post['birthday_day'];

        if (!$email || !$password || !$confirmPassword || !$firstname || !$lastname || !$gender || !$birthdayYear || !$birthdayMonth || !$birthdayDay) return 1;
        if ($password != $confirmPassword) return 2;
        if ($this->emailTaken($email)) return 3;

        $password = sha1($password);
        $birthdate = sprintf("%d-%02d-%02d", intval($birthdayYear), intval($birthdayMonth), intval($birthdayDay));
        $profilePic = $this->uploadProfilePic();
        if (!$profilePic) $profilePic = '';

        $pdo = Database::getInstance();
        $query = "INSERT INTO " . CUser::TABLE . " (email, password, firstname, lastname, gender, birthdate, picture) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $st = $pdo->prepare($query);
        if ($st->execute(array($email, $password, $firstname, $lastname, $gender, $birthdate, $profilePic))) {
            return 0;
        }

        return 4;
    }

    /**
     * @return bool|string
     */
    public function uploadProfilePic()
    {
        //target directory, where to store files
        $targetDir = UPLOADS_PATH;

        //list of allowed mime types
        $allowedTypes = array('image/png', 'image/jpeg', 'image/gif');

        //maximum allowed size per file (bytes)
        $maxFileSize = 1024 * 1024;
        if ($_FILES["image"]["error"]) {
            foreach ($_FILES["image"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    if ($_FILES["image"]["size"][$key] > $maxFileSize) {
                        return false;
                    } else if (!in_array($_FILES["image"]["type"][$key], $allowedTypes)) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }

        //move uploaded files to target directory
        foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
            $id = md5(microtime());
            $ext = '';
            switch ($_FILES["image"]["type"][$key]) {
                case 'image/png':
                    $ext = 'png';
                    break;
                case 'image/jpeg':
                    $ext = 'jpg';
                    break;
                case 'image/gif':
                    $ext = 'gif';
                    break;
            }
            if (move_uploaded_file($tmp_name, $targetDir . $id . '.' . $ext)) {
                return $id . '.' . $ext;
            }
        }

        return false;
    }

    /**
     * Get data related to a user
     *
     * @param integer $id user id
     * @return object /false
     */
    public function getUser($id)
    {
        $pdo = Database::getInstance();
        $st = $pdo->prepare("SELECT * FROM " . CUser::TABLE . " WHERE id=? LIMIT 1");
        $st->execute(array($id));

        if ($st->rowCount() != 1) return false;

        $user = $st->fetch(PDO::FETCH_ASSOC);

        return $this->userFromArray($user);
    }

    /**
     * Get data related to a user
     *
     * @param integer $id user id
     * @return object/false
     */
    public function isOnline($user_id)
    {
        $pdo = Database::getInstance();
        $st = $pdo->prepare("SELECT * FROM users_online WHERE id_user=? LIMIT 1");
        $st->execute(array($user_id));

        if ($st->rowCount() == 0) {
            $query = "INSERT INTO `users_online` (`id_user`) VALUES (?)";
            $st = $pdo->prepare($query);
            $st->execute(array($user_id));
            return true;
        } else {
            $query = "UPDATE `users_online`  SET `date`=NOW() WHERE `id_user`=?";
            $st = $pdo->prepare($query);
            $st->execute(array($user_id));

            $q = "SELECT uo.`id_user`,  u.`firstname`
                    FROM `users_online`  uo
                    LEFT JOIN `user` u ON u.`id` = uo.`id_user`
                    WHERE  TIME_TO_SEC(TIMEDIFF(NOW(),uo.`date`)) < 10";
            $st = $pdo->prepare($q);
            $st->execute(array($user_id));
            $req = $st->fetchAll(PDO::FETCH_ASSOC);
            return $req;
        }

        return 2;
    }

    /**
     * @return bool
     */
    function isUserLogged()
    {
        return boolval($this->loggedIn());
    }

    /**
     * @return int
     */
    function getUserId()
    {
        if ($this->isUserLogged()) {
            return $this->getCurrentUser()->getId();
        }

        return 0;
    }

    /**
     * @return mixed
     */
    function isAdmin()
    {
        return $this->getCurrentUser()->isAdmin();
    }
}