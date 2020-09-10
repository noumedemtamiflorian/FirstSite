<?php


namespace App\Shop\Action;

use App\Framework\Session\SessionInterface;
use App\Shop\Panier;
use App\Shop\Table\ProductTable;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShopDeletePanierAction
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
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    public function __construct(
        RendererInterface $renderer,
        Panier $panier,
        ProductTable $productTable,
        SessionInterface $session
    ) {
        $this->renderer = $renderer;
        $this->panier = $panier;
        $this->productTable = $productTable;
        $this->session = $session;
    }


    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();
        $id  = $params['delete'];
        $this->panier->deleteProduct($id);
        $panier = $this->panier;
        $products = $this->allProductsAdd($this->session['panier']);
        return $this->renderer->render("@shop/panier", compact("panier", "products"));
    }
    private function allProductsAdd(array $panier)
    {
        $valeurs = $products = [];
        foreach ($panier as $key => $value) {
            $valeurs[$key] = str_replace("id", " ", $key);
            $product = $this->productTable->find($valeurs[$key]);
            $price = (float)str_replace('$', " ", $product->price);
            $product->setSomme($price * $panier['id' . $product->id]);
            $product->setQuantite($panier['id' . $product->id]);
            $products[] = $product;
        }
        return $products;
    }
}
