<?php

use App\Auth\Action\LoginAction;
use App\Auth\Action\LoginAttemptAction;
use App\Auth\Action\LogoutAction;
use App\Auth\AuthModule;
use App\Auth\AuthTwigExtension;
use App\Auth\DatabaseAuth;
use App\Auth\ForbiddenMiddleware;
use App\Framework\Auth;
use App\Framework\Session\FlashService;
use App\Framework\Session\SessionInterface;
use Framework\Renderer\TwigRenderer;
use Framework\Router;
use Psr\Container\ContainerInterface;
use function DI\add;
use function DI\get;

return [
    'auth.login' => '/login',
    'auth.logout' => '/logout',
    'twig.extensions' => add([
        get(AuthTwigExtension::class)
    ]),
    AuthModule::class => function (ContainerInterface $container) {
        return new  AuthModule($container);
    },
    Auth::class => get(DatabaseAuth::class),
    LoginAction::class => function (ContainerInterface $container) {
        $renderer = $container->get(TwigRenderer::class);
        return new LoginAction($renderer);
    },
    LoginAttemptAction::class => function (ContainerInterface $container) {
        $renderer = $container->get(TwigRenderer::class);
        $auth = $container->get(DatabaseAuth::class);
        $router = $container->get(Router::class);
        $sessionInterface = $container->get(SessionInterface::class);
        return new LoginAttemptAction($renderer, $auth, $router, $sessionInterface);
    }, LogoutAction::class => function (ContainerInterface $container) {
        $renderer = $container->get(TwigRenderer::class);
        $auth = $container->get(DatabaseAuth::class);
        $flashService = $container->get(FlashService::class);
        return new LogoutAction($renderer, $auth, $flashService);
    },
    ForbiddenMiddleware::class => function (ContainerInterface $container) {
        $loginPath = $container->get('auth.login');
        $sessionInterface = $container->get(SessionInterface::class);
        return new ForbiddenMiddleware($loginPath, $sessionInterface);
    }
];
