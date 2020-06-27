<?php

namespace App\Blog;

use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;

class BlogModule
{
    private $renderer;

    public function __construct(Router $router, RendererInterface $renderer)
    {
        $this->renderer = $renderer;
        if (get_class($renderer) == "Framework\Renderer\PHPRenderer"){
            $this->renderer->addPath('blog', '/views');
        }
        elseif (get_class($renderer) == "Framework\Renderer\TwigRenderer"){
            $this->renderer->addPath('blog', __DIR__.'/views');
        }
        $router->get('/blog', [$this, 'index'], 'blog.index');
        $router->get('/blog/{slug:[a-z\-0-9]+}', [$this, 'show'], 'blog.show');
    }

    public function index(Request $request): string
    {
        return $this->renderer->render('@blog/index');
    }

    public function show(Request $request): string
    {
        return $this->renderer->render(
            '@blog/show',
            [
                'slug' => $request->getAttribute('slug')
            ]
        );
    }
}
