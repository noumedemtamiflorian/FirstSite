<?php

namespace Tests\Blog\BloActions;
require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

use App\Blog\Actions\PostIndexAction;
use App\Blog\Actions\PostShowAction;
use App\Blog\Table\PostTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class  PostShowActionTest extends TestCase
{

    /**
     * @var PostIndexAction
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
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $postTable;

    public function setUp(): void
    {
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->renderer->render()->willReturn('');
        $this->postTable = $this->prophesize(PostTable::class);
        $this->action = new PostShowAction(
            $this->renderer->reveal(),
            $this->postTable->reveal()
        );
    }

    public function testShowRedirect()
    {
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', 9)
            ->withAttribute('slug', 'demo');
        $response = call_user_func_array($this->action, [$request]);
        $this->assertEquals('301', $response->getStatusCode());
    }
}