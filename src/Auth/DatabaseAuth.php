<?php


namespace App\Auth;

use App\Auth\Entity\User;
use App\Auth\Table\UserTable;
use App\Framework\Auth;
use App\Framework\Database\NoRecordException;
use App\Framework\Session\SessionInterface;

class DatabaseAuth implements Auth
{
    /**
     * @var UserTable
     */
    private UserTable $userTable;
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;
    /**
     * @var User
     */
    private $user;

    public function __construct(UserTable $userTable, SessionInterface $session)
    {
        $this->userTable = $userTable;
        $this->session = $session;
    }

    public function login(string $username, string $password)
    {
        if (empty($username) || empty($password)) {
            return null;
        }
        try {
            $user = $this->userTable->findBy('username', $username);
            if ($user && password_verify($password, $user->password)) {
                $this->session->set('auth.user', $user->id);
                return $user;
            }
        } catch (NoRecordException $e) {
            return "dcd";
        }
        return null;
    }

    public function logout()
    {
        $this->session->delete('auth.user');
    }

    public function getUser()
    {
        if ($this->user) {
            return $this->user;
        }
        $userId = $this->session->get("auth.user");
        if ($userId) {
            try {
                $this->user = $this->userTable->find($userId);
                return $this->user;
            } catch (NoRecordException $exception) {
                $this->session->delete('auth.user');
                return null;
            }
        }
    }
}
