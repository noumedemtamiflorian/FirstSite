<?php


namespace App\Framework\Auth;

interface User
{
    /**
     * @return string
     */
    public function getUsername();

    /**
     * @return string[]
     */
    public function getRoles();
}
