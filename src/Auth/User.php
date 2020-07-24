<?php


namespace App\Auth;

use App\Framework\Auth\User as UserInterface;

class User implements UserInterface
{
    public $id;
    public $username;
    public $email;
    public $password;

    public function getUsername()
    {
        return $this->username;
    }

    public function getRoles()
    {
        return [];
    }
}
