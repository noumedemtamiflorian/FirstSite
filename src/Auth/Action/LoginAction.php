<?php


namespace App\Auth\Action;

use App\Auth\DatabaseAuth;
use App\Framework\Auth;
use App\Framework\Session\FlashService;
use App\Framework\Session\SessionInterface;
use App\Framework\Validator;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

class LoginAction
{
    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;
    /**
     * @var Auth
     */
    private Auth $auth;
    /**
     * @var Router
     */
    private Router $router;
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;
    /**
     * @var FlashService
     */
    private FlashService $flashService;
    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        DatabaseAuth $auth,
        Router $router,
        SessionInterface $session,
        FlashService $flashService
    ) {

        $this->renderer = $renderer;
        $this->auth = $auth;
        $this->router = $router;
        $this->session = $session;
        $this->flashService = $flashService;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $errors = $item =null;
        if ($request->getMethod() === "POST") {
            $item = $request->getParsedBody();
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $user = $this->auth->login($item['username'], $item['password']);
                if ($user) {
                    $path = $this->session->get('auth.redirect') ?? $this->router->generateUri('admin');
                    $this->session->delete('auth.redirect');
                    return new  RedirectResponse($path);
                } else {
                    $this->flashService->typeOfFlash(
                        'incorrect',
                        'Identifiant ou mots de passe incorrect'
                    );
                }
            }
            $errors = $validator->getErrors();
        }
        return $this->renderer->render('@auth/login', compact("errors", "item"));
    }

    public function getValidator(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();
        $validator = new  Validator($params);
        $validator->notEmpty("username", "password")
            ->length("username", 3, 50)
            ->length("password", 3, 50);
        return $validator;
    }
}
