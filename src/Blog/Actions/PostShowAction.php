<?php

namespace App\Blog\Actions;

use App\Blog\Entity\Post;
use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostShowAction
{
    private $renderer;
    /**
     * @var PostTable
     */
    private $postTable;
    /**
     * @var Router
     */
    private Router $router;

    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, PostTable $postTable, Router $router)
    {
        $this->renderer = $renderer;
        $this->postTable = $postTable;
        $this->router = $router;
    }

    public function __invoke(Request $request)
    {
        $slug = $request->getAttribute('slug');
        $post = $this->postTable->findWithCategory($request->getAttribute('id'));
        if ($post->slug != $slug) {
            return $this->redirect(
                'blog.show',
                [
                    'slug' => $post->slug,
                    'id' => $post->id
                ]
            );
        }
        return $this->renderer->render(
            '@blog/show',
            [
                'post' => $post
            ]
        );
    }
}
