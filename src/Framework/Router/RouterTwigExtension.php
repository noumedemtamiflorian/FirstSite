<?php

namespace Framework\Router;

use Framework\Router;
use Twig\TwigFunction;

class RouterTwigExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('path', [$this, 'pathFor'])
            , new TwigFunction('is_subpath', [$this, 'isSubpath'])
        ];
    }

    public function pathFor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }

    /**
     * Permet de determiner si une url et parent de path
     * @param string $path
     * @return bool
     */
    public function isSubpath(string $path)
    {
        $uri = ($_SERVER['REQUEST_URI']) ?? '/';
        $expectedUri = $this->router->generateUri($path);
        return strpos($uri, $expectedUri) !== false;
    }
}
