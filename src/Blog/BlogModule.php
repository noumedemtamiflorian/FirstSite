<?php

namespace App\Blog;

use App\Admin\Action\AdminAction;
use App\Blog\Actions\BlogAction;
use Framework\Module;
use Framework\Renderer\TwigRenderer;
use Framework\Router;
use Psr\Container\ContainerInterface;

class BlogModule extends Module
{
    const  DEFINITIONS = __DIR__ . "/config.php";
    const  MIGRATIONS = __DIR__ . "/db/migrations";
    const  SEEDS = __DIR__ . "/db/seeds";

    public function __construct(ContainerInterface $container)
    {
        $renderer = $container->get(TwigRenderer::class);
        $router = $container->get(Router::class);
        $prefix = $container->get('blog.prefix');
        if (get_class($renderer) == "Framework\Renderer\PHPRenderer") {
            $renderer->addPath('blog', '/views');
        } elseif (get_class($renderer) == "Framework\Renderer\TwigRenderer") {
            $renderer->addPath('blog', __DIR__ . '/views');
        }
        $router->get($prefix, BlogAction::class, 'blog.index');
        $router->get($prefix . '/{slug:[a-z\-0-9]+}-{id:[0-9]+}', BlogAction::class, 'blog.show');
        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router->crud($prefix, AdminAction::class, 'admin');
        }
    }
}
