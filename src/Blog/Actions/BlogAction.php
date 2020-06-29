<?php

namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BlogAction
{
    private $renderer;
    /**
     * @var \PDO
     */
    private $router;
    /**
     * @var PostTable
     */
    private $postTable;

    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postTable = $postTable;
    }

    public function __invoke(Request $request)
    {
        if ($request->getAttribute('id')) {
            return $this->show($request);
        }
        return $this->index($request);
    }

    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $posts = $this->postTable->findPaginated(10, $params['p'] ?? 1);
        return $this->renderer->render('@blog/index', compact('posts'));
    }

    /**
     * affiche un article
     * @param Request $request
     * @return \GuzzleHttp\Psr7\MessageTrait|Response|string
     */
    public function show(Request $request)
    {
        $slug = $request->getAttribute('slug');

         $post = $this->postTable->find($request->getAttribute('id'));
        if ($post->slug != $slug) {
            return $this->redirect('blog.show', ['slug' => $post->slug, 'id' => $post->id]);
        }
        return $this->renderer->render(
            '@blog/show',
            [
                'post' => $post
            ]
        );
    }
}
