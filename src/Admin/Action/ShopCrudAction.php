<?php


namespace App\Admin\Action;

use App\Framework\Session\FlashService;
use App\Shop\Table\ProductTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;

class ShopCrudAction extends CrudAction
{
    protected $table = "products";
    protected $viewPath = "@admin/shops";
    protected $routePrefix = "admin.shop";
    protected $message = [
        'modifie' => "Le produit a bien ete modifie",
        'ajouter' => "Le produit a bien ete Ajouter",
        'supprimer' => "Le produit a bien ete supprimer"

    ];
    /**
     * @var ProductTable
     */
    private ProductTable $ProductTable;

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        ProductTable $ProductTable,
        FlashService $flash
    ) {
        parent::__construct($renderer, $router, $ProductTable, $flash);
        $this->ProductTable = $ProductTable;
    }

    protected function getParams(Request $request, $item)
    {
        return $params = array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'price']);
        }, ARRAY_FILTER_USE_KEY);
    }
    protected function getValidator(Request $request)
    {
        return parent::getValidator($request)

            ->required('name', 'price')
            ->priceNull('price')
            ->length('name', 2, 250)
            ->unique('name', $this->table->getTable(), $this->getTable()->getPdo(), $request->getAttribute('id'));
    }
}
