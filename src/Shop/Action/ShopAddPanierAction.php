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
     * @var Panier
     */
    private Panier $panier;
    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;
    /**
     * @var ProductTable
     */
    private ProductTable $productTable;

    public function __construct(RendererInterface $renderer, Panier $panier, ProductTable $productTable)
    {
        $this->renderer = $renderer;
        $this->panier = $panier;
        $this->productTable = $productTable;
    }


    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();
        $id  = $params['add'];
        $this->panier->addProduct($id);
        $panier = $this->panier;
        $products = $this->productTable->findAll()->paginate(12, $params['p'] ?? 1);
        return $this->renderer->render("@shop/index", compact("products", 'panier'));
    }
}
