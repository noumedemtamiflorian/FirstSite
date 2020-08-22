<?php


namespace App\Shop\Action;

use App\Framework\Session\SessionInterface;
use App\Shop\Panier;
use App\Shop\Table\ProductTable;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShopIndexAction
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


    public function __construct(RendererInterface $renderer, ProductTable $productTable, SessionInterface $session)
    {
        $this->renderer = $renderer;
        $this->productTable = $productTable;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $panier = new Panier($this->session, $this->productTable);
        $params = $request->getQueryParams();
        $products = $this->productTable->findAll()->paginate(12, $params['p'] ?? 1);
        return $this->renderer->render("@shop/index", compact("products", 'panier'));
    }
}
