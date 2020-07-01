<?php


namespace App\Framework\Session;

class FlashService
{

    /**
     * @var SessionInterface
     */
    private $session = [];
    private $sessionKey = 'flash';
    private $messages;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     *
     * permet de cree un type de flash en fonction d'un message
     *
     * @param $type
     * @param $message
     */
    public function typeOfFlash($type, $message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash[$type] = $message;
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     *
     * permet de recuperer un message
     *
     * @param string $type
     * @return String|null
     */
    public function get(string $type)
    {
        if (is_null($this->messages)) {
            $this->messages = $this->session->get($this->sessionKey, []);
            $this->session->delete($this->sessionKey);
        }
        if (array_key_exists($type, $this->messages)) {
            return $this->messages[$type];
        }
        return null;
    }
}
