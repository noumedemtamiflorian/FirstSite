<?php


namespace App\Registration\Action;

use App\Auth\UserTable;
use App\Framework\Session\FlashService;
use App\Framework\Session\SessionInterface;
use App\Framework\Validator;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class RegistrationAction
{
    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;
    /**
     * @var UserTable
     */
    private UserTable $userTable;
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;
    /**
     * @var Router
     */
    private Router $router;


    public function __construct(
        RendererInterface $renderer,
        UserTable $userTable,
        SessionInterface $session,
        Router $router
    ) {
        $this->renderer = $renderer;
        $this->userTable = $userTable;
        $this->session = $session;
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $errors = null;
        $item = $request->getParsedBody();
        $validator = $this->getValidator($request);
        if ($validator->isValid()) {
            $this->userTable->insert([
                "username" => $item['name'],
                "email" => $item['email'],
                "password" => password_hash($item['password'], PASSWORD_DEFAULT)
            ]);
            $this->setAuthUser($item['name']);
            $path = $this->session->get('auth.redirect') ?? $this->router->generateUri('admin');
            $this->session->delete('auth.redirect');
            return new  RedirectResponse($path);
        }
        $errors = $validator->getErrors();
        return $this->renderer->render(
            "@registration/registration",
            compact("item", "errors")
        );
    }

    private function getValidator(ServerRequestInterface $request)
    {
        $params = array_merge($request->getParsedBody());
        $validator = new  Validator($params);
        $validator
            ->email("email")
            ->length("name", 3, 50)
            ->length("password", 2)
            ->length("password_confirm", 2)
            ->password("password", $params['password_confirm'] ?? "")
            ->notEmpty("email", "password", "password_confirm", "name");
        return $validator;
    }

    /**
     * Permet de d'editer la valeur de session auth.user;
     * @param UserTable $userTable
     * @param string $username
     */
    private function setAuthUser(string $username): void
    {
        $user = $this->userTable->findUserByUsername($username);
        $this->session->set("auth.user", $user->id);
    }
}
