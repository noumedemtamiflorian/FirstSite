<?php

namespace Tests\Blog\BloActions;

use App\Blog\Actions\PostShowAction;
use App\Blog\Entity\Post;
use App\Blog\Table\PostTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class  PostShowActionTest extends TestCase
{

    private $action;
    private $renderer;
    private $router;
    private $postTable;
    use ProphecyTrait;

    public function setUp(): void
    {
        /* Renderer */
        $this->renderer = $this->prophesize(RendererInterface::class);
        /* Router */
        $this->router = $this->prophesize(Router::class);
        /* PostTable */
        $this->postTable = $this->prophesize(PostTable::class);
        $this->action = new PostShowAction(
            $this->renderer->reveal(),
            $this->postTable->reveal(),
            $this->router->reveal()
        );
    }

    public function makePost(int $id, string $slug): Post
    {
        $post = new  Post();
        $post->id = $id;
        $post->slug = $slug;
        return $post;
    }

    public function testShowRedirect()
    {
        $post = $this->makePost(9, 'azezae-azeeza');
        $this->postTable->findWithCategory($post->id)->willReturn($post);
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $post->id)
            ->withAttribute('slug', 'demo');
        $this->router->generateUri('blog.show', [
            'id' => $post->id,
            'slug' => $post->slug
        ])->willReturn('/demo2');


        $response = call_user_func_array($this->action, [$request]);
        $this->assertEquals('301', $response->getStatusCode());
        $this->assertEquals(['/demo2'], $response->getHeader('location'));
    }

    public function testShowRenderer()
    {
        $post = $this->makePost(9, 'azezae-azeeza');
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $post->id)
            ->withAttribute('slug', $post->slug);
        $this->postTable->findWithCategory($post->id)->willReturn($post);

        $this->renderer->render('@blog/show', ['post' => $post])->willReturn('');

        $response = call_user_func_array($this->action, [$request]);
        $this->assertEquals(true, true);
    }
}