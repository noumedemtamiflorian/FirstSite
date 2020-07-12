<?php

use App\Blog\Actions\CategoryShowAction;
use App\Blog\Actions\PostShowAction;
use App\Blog\BlogWidget;
use App\Blog\Table\CategoryTable;
use function DI\add;
use function DI\get;
use Framework\Renderer\TwigRenderer;
use App\Blog\BlogModule;
use Psr\Container\ContainerInterface;
use App\Blog\Actions\PostIndexAction;
use App\Blog\Table\PostTable;

return
    [
        'blog.prefix' => '/news',
        'admin.widgets' => add([
           get(BlogWidget::class)
        ]),
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
        },
        BlogWidget::class => function (ContainerInterface $container) {
            return new BlogWidget($container->get(TwigRenderer::class), $container->get(PostTable::class));
        }
    ];
