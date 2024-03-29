<?php


namespace App\Framework\Twig;

use Framework\Router;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PagerFantaExtension extends AbstractExtension
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
            new  TwigFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']])
        ];
    }

    public function paginate(
        Pagerfanta $paginatedResults,
        string $route,
        array $routerParams = [],
        array $queryArgs = []
    ) {
        $view = new  TwitterBootstrap4View();
        return $view->render(
            $paginatedResults,
            function (int $page) use ($route, $queryArgs, $routerParams) {
                if ($page > 1) {
                    $queryArgs['p'] = $page;
                }
                return $this->router->generateUri($route, $routerParams, $queryArgs);
            }
        );
    }
}
