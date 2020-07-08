<?php

use App\Blog\Table\CategoryTable;
use App\Framework\Session\PHPSession;
use App\Framework\Session\SessionInterface;
use App\Framework\Twig\FlashExtension;
use App\Framework\Twig\FormExtension;
use App\Framework\Twig\pagerFantaExtension;
use App\Framework\Twig\TextEntension;
use App\Framework\Twig\TimeExtension;
use function DI\factory;
use function DI\get;
use function DI\object;
use Framework\Renderer\RendererInterface;
use    Framework\Renderer\TwigRenderer;
use  Framework\Renderer\TwigRendererFactory;
use Framework\Router\RouterTwigExtension;
use  Psr\Container\ContainerInterface;
use  App\Blog\Table\PostTable;

return [
    'database.host' => 'localhost',
    'database.username' => 'root',
    'database.password' => '',
    'database.name' => 'monsupersite',
    'twig.extensions' => [
        get(RouterTwigExtension::class),
        get(pagerFantaExtension::class),
        get(TextEntension::class),
        get(TimeExtension::class),
        get(FormExtension::class),
        get(FlashExtension::class)
    ],
    'view.path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views',
    SessionInterface::class => object(PHPSession::class),
    TwigRenderer::class => factory(TwigRendererFactory::class),
    RendererInterface::class => object(TwigRenderer::class),
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
    },
   CategoryTable::class => function (ContainerInterface $container) {
        return new  CategoryTable($container->get(PDO::class));
    }
];