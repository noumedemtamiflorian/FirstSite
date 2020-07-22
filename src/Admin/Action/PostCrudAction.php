<?php

namespace App\Admin\Action;

use App\Blog\Entity\Post;
use App\Blog\PostUpload;
use App\Blog\Table\CategoryTable;
use App\Blog\Table\PostTable;
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
    /**
     * @var PostUpload
     */
    private PostUpload $postUpload;

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        PostTable $table,
        FlashService $flash,
        CategoryTable $categoryTable,
        PostUpload $postUpload
    )
    {
        parent::__construct($renderer, $router, $table, $flash);
        $this->categoryTable = $categoryTable;
        $this->postUpload = $postUpload;
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return array|object|null
     */
    protected function getParams(Request $request, $post)
    {
        $params = array_merge($request->getParsedBody(), $request->getUploadedFiles());
        // Uploader le fichier
        $params['image'] = $this->postUpload->upload($params['image'],$post->image);
        $params = array_filter($params, function ($key) {
            return in_array($key, ['name', 'content', 'slug', 'created_at', 'category_id', 'image']);
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
        $validator = parent::getValidator($request)
            ->required('content', 'name', 'slug', 'category_id')
            ->length('content', 10)
            ->length('name', 2, 250)
            ->length('slug', 2, 50)
            ->slug('slug')
            ->extension('image', ['jpg', 'png'])
            ->exists('category_id', $this->categoryTable->getTable(), $this->categoryTable->getPdo())
            ->dateTime('created_at');
        if (is_null($request->getAttribute('id'))) {
            $validator->uploaded('image');
        }
        return $validator;
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
