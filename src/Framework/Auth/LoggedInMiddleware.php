<?php


namespace App\Framework\Auth;

use App\Framework\Auth;
use Psr\Http\Message\ResponseInterface ;
use Psr\Http\Message\ServerRequestInterface ;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface ;

class LoggedInMiddleware implements MiddlewareInterface
{
    /**
     * @var Auth
     */
    private Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->auth->getUser();
        if (is_null($user)) {
            throw  new  ForbiddenException();
        }
        return $handler->handle($request->withAttribute('user', $user));
    }
}
