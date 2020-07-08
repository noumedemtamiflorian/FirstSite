<?php

namespace App\Admin\Action;

use App\Blog\Entity\Post;
use App\Blog\Table\CategoryTable;
use App\Blog\Table\PostTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Session\FlashService;
use DateTime;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostCrudAction extends CrudAction
{
    protected $table = "posts";
    protected $viewPath = "@admin/posts";
    protected $routePrefix = "admin.post";
    /**
     * @var CategoryTable
     */
    private $categoryTable;

    public function __construct(RendererInterface $renderer, Router $router, PostTable $table, FlashService $flash, CategoryTable $categoryTable)
    {
        parent::__construct($renderer, $router, $table, $flash);
        $this->categoryTable = $categoryTable;
    }

    protected function getParams(Request $request)
    {
        $params = array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'content', 'slug', 'created_at', 'category_id']);
        }, ARRAY_FILTER_USE_KEY);
        return $params = array_merge(
            $params,
            [
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );
    }

    protected function getValidator(Request $request)
    {
        return parent::getValidator($request)
            ->required('content', 'name', 'slug', 'category_id')
            ->length('content', 10)
            ->length('name', 2, 250)
            ->length('slug', 2, 50)
            ->slug('slug')
            ->exists('category_id', $this->categoryTable->getTable(), $this->categoryTable->getPdo())
            ->dateTime('created_at');
    }

    protected function getNewEntity()
    {
        $post = new Post();
        $post->created_at = new DateTime();
        return $post;
    }

    protected function formParams(array $params)
    {
        $params['categories'] = $this->categoryTable->findList();
        return $params;
    }
}
