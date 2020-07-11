<?php


namespace App\Admin\Action;

use App\Blog\Table\CategoryTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Session\FlashService;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoryCrudAction extends CrudAction
{
    protected $table = "categories";
    protected $viewPath = "@admin/categories";
    protected $routePrefix = "admin.category";
    protected $message = [
        'modifie' => "La categorie a bien ete modifie",
        'ajouter' => "La categorie a bien ete Ajouter",
        'supprimer' => "La categorie a bien ete supprimer"
    ];

    public function __construct(RendererInterface $renderer, Router $router, CategoryTable $table, FlashService $flash)
    {
        parent::__construct($renderer, $router, $table, $flash);
    }

    protected function getParams(Request $request)
    {
        return $params = array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'slug']);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function getValidator(Request $request)
    {
        return parent::getValidator($request)

            ->required('name', 'slug')
            ->length('name', 2, 250)
            ->length('slug', 2, 50)
            ->unique('slug',$this->table->getTable(),$this->getTable()->getPdo(),$request->getAttribute('id'))
            ->slug('slug');
    }
}
