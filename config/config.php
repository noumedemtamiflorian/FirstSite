<?php

use    Framework\Renderer\TwigRenderer;
use  Framework\Renderer\TwigRendererFactory;
use   Framework\Router;
use  Psr\Container\ContainerInterface;
use  App\Blog\Table\PostTable;

return [
    'database.host' => 'localhost',
    'database.username' => 'root',
    'database.password' => 'saintjude',
    'database.name' => 'monsupersite',
    'twig.extensions' => [
        \DI\get(Router\RoutetTwigExtension::class)
    ],
    'view.path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views',


    TwigRenderer::class => \DI\factory(TwigRendererFactory::class),


    Router::class => \DI\object(),

    \PDO::class => function (ContainerInterface $container) {
        return new PDO(
            'mysql:host=' . $container->get('database.host')
            . ';dbname=' . $container->get('database.name')
            , $container->get('database.username')
            , $container->get('database.password')
            , [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    },


    PostTable::class => function (ContainerInterface $container) {
        return new  PostTable($container->get(PDO::class));
    }
];