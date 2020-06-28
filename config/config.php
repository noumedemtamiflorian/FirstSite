<?php

use    Framework\Renderer\TwigRenderer;
use  Framework\Renderer\TwigRendererFactory;
use   Framework\Router;

return [
    'twig.extensions' =>[
        \DI\get(Router\RoutetTwigExtension::class)
    ] ,
    'view.path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views',
    TwigRenderer::class => \DI\factory(TwigRendererFactory::class),
    Router::class => \DI\object()
];