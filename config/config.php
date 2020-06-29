<?php

use App\Framework\Twig\pagerFantaExtension;
use App\Framework\Twig\TextEntension;
use    Framework\Renderer\TwigRenderer;
use  Framework\Renderer\TwigRendererFactory;
use   Framework\Router;
use Framework\Router\RouterTwigExtension;
use  Psr\Container\ContainerInterface;
use  App\Blog\Table\PostTable;

return [
    'database.host' => 'localhost',
    'database.username' => 'root',
    'database.password' => 'saintjude',
    'database.name' => 'monsupersite',
    'twig.extensions' => [
        \DI\get(RouterTwigExtension::class),
        \DI\get(pagerFantaExtension::class),
        \DI\get(TextEntension::class),
        \DI\get(\App\Framework\Twig\TimeExtension::class)
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