<?php


namespace App\Auth;

use App\Framework\Auth\ForbiddenException;
use App\Framework\Session\FlashService;
use App\Framework\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;

class ForbiddenMiddleware implements MiddlewareInterface
{
    private string $loginPath;
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;


    public function __construct(string $loginPath, SessionInterface $session)
    {
        $this->loginPath = $loginPath;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ForbiddenException $exception) {
            $this->session->set('auth.redirect', $request->getUri()->getPath());
            (new  FlashService($this->session))->typeOfFlash('Abscence_compte', 'Vous devez posseder un compte pour acceder a cette page');
            return new   RedirectResponse($this->loginPath);
        }
    }
}
