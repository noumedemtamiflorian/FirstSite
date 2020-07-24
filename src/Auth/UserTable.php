<?php


namespace App\Auth;

use App\Framework\Database\Table;

class UserTable extends Table
{
    protected $table = "users";
    protected $entity = User::class;
}
