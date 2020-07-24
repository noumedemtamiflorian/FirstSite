<?php


namespace App\Auth\Action;

use App\Auth\DatabaseAuth;
use App\Framework\Session\FlashService;
use App\Framework\Session\SessionInterface;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

class LogoutAction
{
    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;
    /**
     * @var DatabaseAuth
     */
    private DatabaseAuth $auth;
    /**
     * @var FlashService
     */
    private FlashService $flashService;

    public function __construct(
        RendererInterface $renderer,
        DatabaseAuth $auth,
        FlashService $flashService
    ) {

        $this->renderer = $renderer;
        $this->auth = $auth;
        $this->flashService = $flashService;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $this->auth->logout();
        $this->flashService->typeOfFlash('succes', "vous etes maintenant deconnecter");
        return new  RedirectResponse('/');
    }
}
