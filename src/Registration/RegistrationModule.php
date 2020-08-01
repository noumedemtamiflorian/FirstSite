<?php


namespace App\Registration;

use App\Registration\Action\RegistrationAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;

class RegistrationModule extends Module
{

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
        $this->router = $router;
        $this->renderer = $renderer;
        $this->renderer->addPath("registration", __DIR__ . "/views");
        $this->router->get("/registration", RegistrationAction::class, "registration.index");
        $this->router->post("/registration", RegistrationAction::class);
    }
}
