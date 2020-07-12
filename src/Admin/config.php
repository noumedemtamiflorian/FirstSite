<?php

use App\Admin\Action\CategoryCrudAction;
use App\Admin\Action\DashboardAction;
use App\Admin\Action\PostCrudAction;
use App\Admin\AdminModule;
use App\Admin\AdminTwigExtenxion;
use App\Blog\Table\CategoryTable;
use App\Blog\Table\PostTable;
use App\Framework\Session\FlashService;
use function DI\get;
use function DI\object;
use Framework\Renderer\TwigRenderer;
use Framework\Router;
use Psr\Container\ContainerInterface;

return
    [
        'admin.prefix' => '/admin',
        'admin.widgets' => [

        ],
        AdminTwigExtenxion::class => object()->constructor(get('admin.widgets'))
        ,
        AdminModule::class => function (ContainerInterface $container) {
            return new  AdminModule($container);
        },
        PostCrudAction::class => function (ContainerInterface $container) {
            $renderer = $container->get(TwigRenderer::class);
            $router = $container->get(Router::class);
            $postTable = $container->get(PostTable::class);
            $FlashService = $container->get(FlashService::class);
            $categoryTable = $container->get(CategoryTable::class);
            return new   PostCrudAction($renderer, $router, $postTable, $FlashService, $categoryTable);
        },
        CategoryCrudAction::class => function (ContainerInterface $container) {
            $renderer = $container->get(TwigRenderer::class);
            $router = $container->get(Router::class);
            $categoryTable = $container->get(CategoryTable::class);
            $FlashService = $container->get(FlashService::class);
            return new   CategoryCrudAction($renderer, $router, $categoryTable, $FlashService);
        },
        DashboardAction::class => \DI\object()->constructorParameter('widgets', \DI\get('admin.widgets'))
    ];
