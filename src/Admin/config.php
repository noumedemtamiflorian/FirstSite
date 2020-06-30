<?php

use App\Admin\Action\AdminAction;
use App\Admin\AdminModule;
use App\Blog\Table\PostTable;
use Framework\Renderer\TwigRenderer;
use Framework\Router;
use Psr\Container\ContainerInterface;

return
    [
        'admin.prefix' => '/admin',
        AdminModule::class => function (ContainerInterface $container) {
            return new  AdminModule($container->get(TwigRenderer::class));
        },
        AdminAction::class => function (ContainerInterface $container) {
            $renderer = $container->get(TwigRenderer::class);
            $router = $container->get(Router::class);
            $postTable = $container->get(PostTable::class);
            return new   AdminAction($renderer, $router, $postTable);
        }
    ];
