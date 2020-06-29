<?php

use Framework\Renderer\TwigRenderer;
use Framework\Router;
use App\Blog\BlogModule;
use Psr\Container\ContainerInterface;
use App\Blog\Actions\BlogAction;
use App\Blog\Table\PostTable;

return
    [
        'blog.prefix' => '/blog',
        BlogModule::class => function (ContainerInterface $container) {
            return new BlogModule($container->get('blog.prefix'), $container->get(Router::class), $container->get(TwigRenderer::class));
        },
        BlogAction::class => function (ContainerInterface $container) {
            return new BlogAction($container->get(TwigRenderer::class), $container->get(Router::class), $container->get(PostTable::class));
        }
    ];
