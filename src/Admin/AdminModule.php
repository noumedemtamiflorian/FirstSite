<?php


namespace App\Admin;

use App\Admin\Action\CategoryCrudAction;
use App\Admin\Action\PostCrudAction;
use Framework\Module;
use Framework\Renderer\TwigRenderer;
use Framework\Router;
use Psr\Container\ContainerInterface;

class AdminModule extends Module
{
    const  DEFINITIONS = __DIR__ . "/config.php";

    public function __construct(ContainerInterface $container)
    {
        $renderer = $container->get(TwigRenderer::class);
        $router = $container->get(Router::class);
        $prefix = $container->get('admin.prefix');
        $renderer->addPath('admin', __DIR__ . '/views');
        $router->crudPost("$prefix/posts", PostCrudAction::class, 'admin.post');
        $router->crudPost("$prefix/categories", CategoryCrudAction::class, 'admin.category');
    }
}
