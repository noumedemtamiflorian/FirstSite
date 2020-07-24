<?php


namespace App\Auth;

use App\Auth\Action\LoginAction;
use App\Auth\Action\LoginAttemptAction;
use App\Auth\Action\LogoutAction;
use Framework\Module;
use Framework\Renderer\TwigRenderer;
use Framework\Router;
use Psr\Container\ContainerInterface;

class AuthModule extends Module
{
    const  DEFINITIONS = __DIR__ . "/config.php";
    const  MIGRATIONS = __DIR__ . "/db/migrations";
    const  SEEDS = __DIR__ . "/db/seeds";

    public function __construct(ContainerInterface $container)
    {
        $renderer = $container->get(TwigRenderer::class);
        $router = $container->get(Router::class);
        $prefix = $container->get('auth.login');
        $prefixLogout = $container->get('auth.logout');
        $renderer->addPath('auth', __DIR__.'/view');
        $router->get($prefix, LoginAction::class, 'auth.login');
        $router->post($prefix, LoginAttemptAction::class);
        $router->post($prefixLogout, LogoutAction::class, 'auth.logout');
    }
}
