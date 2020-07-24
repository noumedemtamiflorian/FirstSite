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

class LoginAttemptAction
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
     * @var Router
     */
    private Router $router;
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;
    use  RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        DatabaseAuth $auth,
        Router $router,
        SessionInterface $session
    ) {

        $this->renderer = $renderer;
        $this->auth = $auth;
        $this->router = $router;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();
        $user = $this->auth->login($params['username'], $params['password']);
        if ($user) {
            $path = $this->session->get('auth.redirect') ?? $this->router->generateUri('admin');
            $this->session->delete('auth.redirect');
            return new  RedirectResponse($path);
        } else {
            (new  FlashService($this->session))->typeOfFlash('incorrect', 'Identifiant ou mots de passe incorrect');
            return $this->redirect('auth.login');
        }
    }
}
