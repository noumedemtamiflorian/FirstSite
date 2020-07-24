<?php


namespace App\Auth\Action;


use App\Auth\DatabaseAuth;
use App\Framework\Session\FlashService;
use App\Framework\Session\SessionInterface;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouterInterface;

class LoginAction
{
    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;

    public function __construct(
        RendererInterface $renderer
    )
    {

        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request)
    {

        return $this->renderer->render('@auth/login');
    }
}