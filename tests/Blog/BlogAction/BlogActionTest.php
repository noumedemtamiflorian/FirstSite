<?php

namespace Tests\Blog\BloActions;

use App\Blog\Actions\BlogAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class  BlogActionTest extends TestCase
{

    /**
     * @var BlogAction
     */
    private $action;
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $renderer;
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $router;
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $pdo;

    public function setUp(): void
    {
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->renderer-render()->willReturn('');
        $this->pdo = $this->prophesize(\PDO::class);
        $this->router = $this->prophesize(Router::class);
        $this->action = new BlogAction(
            $this->renderer->reveal(),
            $this->pdo->reveal(),
            $this->router->reveal()
        );
    }

    public function testShowRedirect()
    {
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', 9)
            ->withAttribute('slug', 'demo');
        $response = call_user_func_array( $this->action , [$request]);
        $this->assertEquals('301', $response->getStatusCode());
    }
}