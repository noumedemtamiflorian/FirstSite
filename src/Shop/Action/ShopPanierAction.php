<?php


namespace App\Shop\Action;

use App\Framework\Session\SessionInterface;
use App\Shop\Panier;
use App\Shop\Table\ProductTable;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShopPanierAction
{
    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;
    /**
     * @var ProductTable
     */
    private ProductTable $productTable;

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
        $params = $request->getQueryParams();
        $panier = new Panier($this->session, $this->productTable);
        if (isset($params['add'])) {
            $panier->addProduct($params['add']);
        } elseif (isset($params['delete'])) {
            $panier->delete($params['delete']);
        }
        $products = $this->allProductsAdd($this->session['panier']);
        return $this->renderer->render("@shop/panier", compact("panier", "products"));
    }

    private function allProductsAdd(array $panier)
    {
        $valeurs = $products = [];
        foreach ($panier as $key => $value) {
            $valeurs[$key] = str_replace("id", " ", $key);
            $product = $this->productTable->find($valeurs[$key]);
            $price = (double)str_replace('$', " ", $product->price);
            $product->setSomme($price * $panier['id' . $product->id]);
            $product->setQuantite($panier['id' . $product->id]);
            $products[] = $product;
        }
        return $products;
    }

    private function getPanierSimple($panier)
    {
        $products = [];
        foreach ($panier as $key => $value) {
            $products[str_replace("id", " ", $key)] = $value;
        }
        return $products;
    }
}
