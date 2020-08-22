<?php


namespace App\Shop\Action;

use App\Framework\Session\SessionInterface;
use App\Shop\Panier;
use App\Shop\Table\ProductTable;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShopAddPanierAction
{
    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;
    /**
     * @var ProductTable
     */
    private ProductTable $productTable;
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    public function __construct(
        RendererInterface $renderer,
        ProductTable $productTable,
        SessionInterface $session
    ) {
        $this->renderer = $renderer;
        $this->session = $session;
        $this->productTable = $productTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($request->getMethod() === "POST") {
            dump("post");
        }
    }
}
