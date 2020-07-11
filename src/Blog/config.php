<?php

use App\Blog\Actions\CategoryShowAction;
use App\Blog\Actions\PostShowAction;
use App\Blog\Table\CategoryTable;
use Framework\Renderer\TwigRenderer;
use App\Blog\BlogModule;
use Psr\Container\ContainerInterface;
use App\Blog\Actions\PostIndexAction;
use App\Blog\Table\PostTable;

return
    [
        'blog.prefix' => '/news',
        BlogModule::class => function (ContainerInterface $container) {
            return new BlogModule($container);
        },
        PostIndexAction::class => function (ContainerInterface $container) {
            return new PostIndexAction($container->get(TwigRenderer::class), $container->get(PostTable::class), $container->get(CategoryTable::class));
        },
        PostShowAction::class => function (ContainerInterface $container) {
            return new PostShowAction($container->get(TwigRenderer::class), $container->get(PostTable::class));
        },
        CategoryShowAction::class => function (ContainerInterface $container) {
            return new CategoryShowAction($container->get(TwigRenderer::class), $container->get(PostTable::class), $container->get(CategoryTable::class));
        }
    ];
