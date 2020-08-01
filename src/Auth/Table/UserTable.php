<?php


namespace App\Auth\Table;

use App\Auth\Entity\User;
use App\Framework\Database\Table;

class UserTable extends Table
{
    protected $table = "users";
    protected $entity = User::class;
}
