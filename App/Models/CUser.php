<?php

namespace App\Models;

/**
 * User
 */
class CUser
{
    const TABLE = TBL_USER;

    protected $id;
    protected $email;
    protected $username;
    protected $password;
    protected $firstname;
    protected $lastname;
    protected $gender;
    protected $birthdate;
    protected $picture;
    protected $admin;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    public function isAdmin()
    {
        return (boolean)$this->getAdmin();
    }

    function getAge($birthday)
    {
        $age = strtotime($birthday);

        if ($age === false) {
            return false;
        }

        list($y1, $m1, $d1) = explode("-", date("Y-m-d", $age));

        $now = strtotime("now");

        list($y2, $m2, $d2) = explode("-", date("Y-m-d", $now));

        $age = $y2 - $y1;

        if ((int)($m2 . $d2) < (int)($m1 . $d1))
            $age -= 1;

        return $age;
    }
}