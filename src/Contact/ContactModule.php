<?php


namespace App\Contact;

use App\Contact\Action\ContactAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Container\ContainerInterface;

class ContactModule extends Module
{
    const DEFINITIONS = __DIR__ . "/config.php";
    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;
    /**
     * @var Router
     */
    private Router $router;

    public function __construct(RendererInterface $renderer, Router $router)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->renderer->addPath("contact", __DIR__ . "/views");
        $this->router->get("/contact", ContactAction::class, "contact.index");
        $this->router->post("/contact", ContactAction::class);
    }
}
