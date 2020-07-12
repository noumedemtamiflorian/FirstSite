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
 * Register and match route
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
     * @param string $path
     * @param $callable
     * @param string|null $name
     */
    public function post(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, new MiddlewareApp($callable), ['POST'], $name));
    }

    /**
     * @param string $path
     * @param $callable
     * @param string|null $name
     */
    public function delete(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, new MiddlewareApp($callable), ['DELETE'], $name));
    }

    public function crudPost(string $prefix, $callable, string $prefixName)
    {
        $this->get("$prefix", $callable, "$prefixName.index");
        $this->get("$prefix/{id:\d+}", $callable, "$prefixName.edit");
        $this->post("$prefix/{id:\d+}", $callable);
        $this->get("$prefix/new", $callable, "$prefixName.create");
        $this->post("$prefix/new", $callable);
        $this->delete("$prefix/{id:\d+}", $callable, "$prefixName.delete");
    }

    /**
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

    public function generateUri(string $name, array $params = [], array $queryParams = []): ?string
    {
        $uri = $this->router->generateUri($name, $params);
        if (!empty($queryParams)) {
            return $uri . '?' . http_build_query($queryParams);
        }
        return $uri;
    }
}
