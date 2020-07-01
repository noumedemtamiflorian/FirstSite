<?php


namespace App\Framework\Session;

interface SessionInterface
{
    /**
     *
     * recupere un information en Session
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     *
     * Ajoute une information en Session
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value):void;


    /**
     *
     * Supprime une information dans la Session
     *
     * @param string $key
     */
    public function delete(string $key):void;
}
