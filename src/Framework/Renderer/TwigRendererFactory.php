<?php

namespace Framework\Renderer;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigRendererFactory
{
    private $twig;

    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        $debug = $container->get('env') !== 'production';
        $loader = new FilesystemLoader();
        $twig = new Environment($loader, [
            'debug' => $debug /*,
            'cache' => $debug ? false : 'tmp/views' ,
            'auto_reload' => $debug */
        ]);
        $twig->addExtension(new DebugExtension());
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($container->get(get_class($extension)));
            }
        }
        $this->twig = $twig;
        return new TwigRenderer($twig);
    }

    /**
     * @return mixed
     */
    public function getTwig()
    {
        return $this->twig;
    }
}
