<?php


namespace App\Shop;

use App\Shop\Action\ShopAddPanierAction;
use App\Shop\Action\ShopDeletePanierAction;
use App\Shop\Action\ShopIndexAction;
use App\Shop\Action\ShopPanierAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;

class ShopModule extends Module
{
    const MIGRATIONS = __DIR__ . "/db/migrations";
    const  SEEDS = __DIR__ . "/db/seeds";
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
        $this->renderer->addPath("shop", __DIR__ . "/views");
        $this->router->get("/shop", ShopIndexAction::class, "shop.index");
        $this->router->get("/shop/panier", ShopPanierAction::class, "shop.panier");
        $this->router->get("/shop/addpanier", ShopAddPanierAction::class, "shop.addpanier");
        $this->router->get("/shop/deletepanier", ShopDeletePanierAction::class, "shop.deletepanier");
    }
}
