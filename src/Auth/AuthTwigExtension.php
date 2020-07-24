<?php


namespace App\Auth;

use App\Framework\Auth;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AuthTwigExtension extends AbstractExtension
{
    /**
     * @var Auth
     */
    private Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function getFunctions()
    {
        return [
          new  TwigFunction('current_user', [$this->auth,'getUser'])
        ];
    }
}
