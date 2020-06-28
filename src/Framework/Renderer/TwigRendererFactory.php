<?php

namespace Framework\Renderer;

use Framework\Router\RoutetTwigExtension;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRendererFactory
{
    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        $viewPath = $container->get('view.path');
        $loader = new FilesystemLoader($viewPath);
        $twig = new Environment($loader, []);
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($container->get(RoutetTwigExtension::class));
            }
        }
        return new  TwigRenderer($loader, $twig);
    }
}
