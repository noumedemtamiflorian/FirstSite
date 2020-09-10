<?php

namespace Framework;

use App\Framework\Middleware\RoutePrefixedMiddleware;
use DI\ContainerBuilder;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class App implements RequestHandlerInterface
{
    /**
     * Router
     * @var Router
     */
    private $router;
    /**
     * List of modules
     * @var array
     */
    private $modules = [];
    /**
     * @var string
     */
    private $definition;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var string[]
     */
    private $middleware;
    /**
     * @var  int
     */
    private $index = 0;

    public function __construct(string $definition)
    {
        $this->definition = $definition;
    }

    public function addModule(string $module)
    {
        $this->modules[] = $module;
        return $this;
    }

    public function pipe(string $middleware, ?array $routerPrefix = null)
    {
        if ($routerPrefix === null) {
            $this->middleware[] = $middleware;
        } else {
            $this->middleware[] = new  RoutePrefixedMiddleware($this->container, $routerPrefix, $middleware);
        }
        return $this;
    }


    public function run(ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->modules as $module) {
            $this->getContainer()->get($module);
        }
        return $this->handle($request);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();

        if (is_null($middleware)) {
            throw  new  Exception('aucun midlleware m\' a intercepter cette requete');
        } elseif (is_callable($middleware)) {
            return call_user_func_array($middleware, [$request, [$this, 'handle']]);
        } elseif ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        }
    }

    /**
     * @return ContainerInterface
     * @throws Exception
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container == null) {
            $builder = new ContainerBuilder();
            $builder->addDefinitions($this->definition);
            foreach ($this->modules as $module) {
                if ($module::DEFINITIONS) {
                    $builder->addDefinitions($module::DEFINITIONS);
                }
            }
            $this->container = $builder->build();
        }
        return $this->container;
    }

    private function getMiddleware()
    {
        if (array_key_exists($this->index, $this->middleware)) {
            if (is_string($this->middleware[$this->index])) {
                $middleware = $this->container->get($this->middleware[$this->index]);
            } else {
                $middleware = $this->middleware[$this->index];
            }
            $this->index++;
            return $middleware;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getModules(): array
    {
        return $this->modules;
    }
}
