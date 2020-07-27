<?php

namespace Framework;

use Framework\Router\

MiddlewareApp;
use Framework\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\Route as ZendRoute;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Expressive\Router\FastRouteRouter;

/**
 * Enregistrer , Match , Generer une URI ou Route
 */
class Router
{
    /**
     * @var FastRouteRouter
     */
    private $router;

    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * Permet d'enregistrer une route en methode GET
     * @param string $path
     * @param $callable
     * @param string|null $name
     */
    public function get(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, new
        MiddlewareApp($callable), ['GET'], $name));
    }

    /**
     * Permet d'enregistrer une route en methode POST
     * @param string $path
     * @param $callable
     * @param string|null $name
     */
    public function post(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, new MiddlewareApp($callable), ['POST'], $name));
    }

    /**
     * Permet d'enregistrer une route en methode POST
     * @param string $path
     * @param $callable
     * @param string|null $name
     */
    public function delete(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, new MiddlewareApp($callable), ['DELETE'], $name));
    }

    /**
     * Permet de matcher une route(un chemin)
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request);
        if ($result->isSuccess()) {
            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedRoute()->getMiddleware()->getCallback(),
                $result->getMatchedParams()
            );
        }
        return null;
    }

    /**
     * Permet de generer une route (URI)
     * @param string $name
     * @param array $params
     * @param array $queryParams
     * @return string|null
     */
    public function generateUri(string $name, array $params = [], array $queryParams = []): ?string
    {
        $uri = $this->router->generateUri($name, $params);
        if (!empty($queryParams)) {
            return $uri . '?' . http_build_query($queryParams);
        }
        return $uri;
    }
}
