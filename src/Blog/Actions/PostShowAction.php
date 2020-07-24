<?php

namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostShowAction
{
    private $renderer;
    /**
     * @var PostTable
     */
    private $postTable;

    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, PostTable $postTable)
    {
        $this->renderer = $renderer;
        $this->postTable = $postTable;
    }

    public function __invoke(Request $request)
    {
        $slug = $request->getAttribute('slug');
        $post = $this->postTable->findWithCategory($request->getAttribute('id'));
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
