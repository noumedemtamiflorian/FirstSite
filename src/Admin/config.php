<?php

use App\Admin\Action\CategoryCrudAction;
use App\Admin\Action\DashboardAction;
use App\Admin\Action\PostCrudAction;
use App\Admin\AdminModule;
use App\Admin\AdminTwigExtenxion;
use App\Blog\PostUpload;
use App\Blog\Table\CategoryTable;
use App\Blog\Table\PostTable;
use App\Framework\Session\FlashService;
use Framework\Renderer\RendererInterface;
use function DI\create;
use function DI\get;
use Framework\Router;
use Psr\Container\ContainerInterface;

return
    [
        'admin.prefix' => '/admin',
        'admin.widgets' => [

        ],
        AdminTwigExtenxion::class => create()->constructor(get('admin.widgets'))
        ,
        AdminModule::class => function (ContainerInterface $container) {
            return new  AdminModule($container);
        },
        PostCrudAction::class => function (ContainerInterface $container) {
            $renderer = $container->get(RendererInterface::class);
            $router = $container->get(Router::class);
            $postTable = $container->get(PostTable::class);
            $FlashService = $container->get(FlashService::class);
            $categoryTable = $container->get(CategoryTable::class);
            $PostUpload = $container->get(PostUpload::class);
            return new   PostCrudAction(
                $renderer,
                $router,
                $postTable,
                $FlashService,
                $categoryTable,
                $PostUpload
            );
        },
        CategoryCrudAction::class => function (ContainerInterface $container) {
            $renderer = $container->get(RendererInterface::class);
            $router = $container->get(Router::class);
            $categoryTable = $container->get(CategoryTable::class);
            $FlashService = $container->get(FlashService::class);
            return new   CategoryCrudAction($renderer, $router, $categoryTable, $FlashService);
        },
        DashboardAction::class => function (ContainerInterface $container) {
            $twig = $container->get(RendererInterface::class);
            $widgets = $container->get('admin.widgets');
            return new  DashboardAction($twig, $widgets);
        }
    ];
