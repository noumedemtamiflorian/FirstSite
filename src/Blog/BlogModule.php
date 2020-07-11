<?php

namespace App\Blog;

use App\Admin\Action\PostCrudAction;
use App\Blog\Actions\CategoryShowAction;
use App\Blog\Actions\PostIndexAction;
use App\Blog\Actions\PostShowAction;
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
        $router->get($prefix, PostIndexAction::class, 'blog.index');
        $router->get($prefix . '/{slug:[a-z\-0-9]+}-{id:[0-9]+}', PostShowAction::class, 'blog.show');
        $router->get($prefix . '/category/{slug:[a-z\-0-9]+}', CategoryShowAction::class, 'blog.category');
    }
}
