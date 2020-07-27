<?php


namespace App\Admin;

use App\Admin\Action\CategoryCrudAction;
use App\Admin\Action\DashboardAction;
use App\Admin\Action\PostCrudAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRenderer;
use Framework\Router;
use Psr\Container\ContainerInterface;

class AdminModule extends Module
{
    const  DEFINITIONS = __DIR__ . "/config.php";
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $renderer = $container->get(RendererInterface::class);
        $router = $container->get(Router::class);
        $prefix = $container->get('admin.prefix');
        $renderer->addPath('admin', __DIR__ . '/views');
        $this->crudPost("$prefix/posts", PostCrudAction::class, 'admin.post');
        $this->crudPost("$prefix/categories", CategoryCrudAction::class, 'admin.category');
        $router->get($prefix, DashboardAction::class, 'admin');
        if ($renderer instanceof TwigRenderer) {
            $adminTwigExtension = $container->get(AdminTwigExtenxion::class);
            $renderer->getTwig()->addExtension($adminTwigExtension);
        }
    }
    public function crudPost(string $prefix, $callable, string $prefixName)
    {
        $router =  $this->container->get(Router::class);
        $router->get("$prefix", $callable, "$prefixName.index");
        $router->get("$prefix/{id:\d+}", $callable, "$prefixName.edit");
        $router->post("$prefix/{id:\d+}", $callable);
        $router->get("$prefix/new", $callable, "$prefixName.create");
        $router->post("$prefix/new", $callable);
        $router->delete("$prefix/{id:\d+}", $callable, "$prefixName.delete");
    }
}
