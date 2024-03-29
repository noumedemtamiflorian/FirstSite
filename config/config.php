<?php

use App\Blog\PostUpload;
use App\Blog\Table\CategoryTable;
use App\Framework\Middleware\CsrfMiddleware;
use App\Framework\Middleware\DispatcherMiddleware;
use App\Framework\Session\PHPSession;
use App\Framework\Session\SessionInterface;
use App\Framework\Twig\CsrfExtension;
use App\Framework\Twig\EchoExtension;
use App\Framework\Twig\FlashExtension;
use App\Framework\Twig\FormExtension;
use App\Framework\Twig\PagerFantaExtension;
use App\Framework\Twig\TextExtension;
use App\Framework\Twig\TimeExtension;
use App\Framework\Upload;
use App\Shop\TwigExtension\NumberFormatExtension;
use function DI\create;
use function DI\factory;
use function DI\get;
use function DI\object;
use Framework\Renderer\RendererInterface;
use    Framework\Renderer\TwigRenderer;
use  Framework\Renderer\TwigRendererFactory;
use Framework\Router\RouterTwigExtension;
use Middlewares\Whoops;
use  Psr\Container\ContainerInterface;
use  App\Blog\Table\PostTable;
use App\Framework\Twig\AgoExtension;
use Whoops\Run;

return [

    'env' => \DI\env('ENV', 'production'),
    'database.host' => '127.0.0.1',
    'database.username' => 'noumedem',
    'database.password' => 'root',
    'database.name' => 'monsupersite',
    'twig.extensions' => [
        get(RouterTwigExtension::class),
        get(PagerFantaExtension::class),
        get(TextExtension::class),
        get(TimeExtension::class),
        get(FormExtension::class),
        get(FlashExtension::class),
        get(EchoExtension::class),
        get(CsrfExtension::class),
        get(NumberFormatExtension::class),
        get(AgoExtension::class)
    ],
    SessionInterface::class => create(PHPSession::class),
    CsrfMiddleware::class => create()->constructor(get(SessionInterface::class)),
    RendererInterface::class => factory(TwigRendererFactory::class),
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
    },
    DispatcherMiddleware::class => function (ContainerInterface $container) {
        return new   DispatcherMiddleware($container);
    },
    PostUpload::class => function () {
        return new  PostUpload();
    },
    Upload::class => function () {
        return new Upload();
    }
];