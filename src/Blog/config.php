<?php

use App\Blog\Actions\CategoryShowAction;
use App\Blog\Actions\PostShowAction;
use App\Blog\BlogWidget;
use App\Blog\Table\CategoryTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use function DI\add;
use function DI\get;
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
            $renderer = $container->get(RendererInterface::class);
            $postTable = $container->get(PostTable::class);
            $categoryTable = $container->get(CategoryTable::class);
            return new PostIndexAction($renderer, $postTable, $categoryTable);
        },
        PostShowAction::class => function (ContainerInterface $container) {
            $renderer = $container->get(RendererInterface::class);
            $postTable = $container->get(PostTable::class);
            $router = $container->get(Router::class);
            return new PostShowAction($renderer, $postTable, $router);
        },
        CategoryShowAction::class => function (ContainerInterface $container) {
            $renderer = $container->get(RendererInterface::class);
            $postTable = $container->get(PostTable::class);
            $categoryTable = $container->get(CategoryTable::class);
            return new CategoryShowAction($renderer, $postTable, $categoryTable);
        },
        BlogWidget::class => function (ContainerInterface $container) {
            $renderer = $container->get(RendererInterface::class);
            $postTable = $container->get(PostTable::class);
            return new BlogWidget($renderer, $postTable);
        }
    ];
