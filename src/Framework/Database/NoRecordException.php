<?php


namespace App\Framework\Database;

class NoRecordException extends \Exception
{
    public function __construct()
    {
        parent::__construct("", 0);
    }

    public function __toString()
    {
        return $this->message;
    }
}
