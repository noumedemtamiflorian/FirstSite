<?php

namespace Tests\Framework;

use Framework\Renderer\PHPRenderer;
use PHPUnit\Framework\TestCase;


class PHPRendererTest extends TestCase
{

    /**
     * @var PHPRenderer
     */
    private $renderer;

    public function setUp(): void
    {
        $this->renderer = new PHPRenderer();
    }

    public function testRendererTheRightPath()
    {
        $this->renderer->addPath("blog", __DIR__ . "/view");
        $content = $this->renderer->render('@blog/demo');
        $this->assertEquals('salut les gens', $content);
    }

    public function testRendererTheDefaultPath()
    {
        $this->renderer->addPath(__DIR__ . '/view');
        $content = $this->renderer->render('demo');
        $this->assertEquals('salut les gens', $content);
    }

    public function testRendererWithParams()
    {
        $this->renderer->addPath(__DIR__ . '/view');
        $content = $this->renderer->render('demoparams', ['nom' => 'Marc']);
        $this->assertEquals('salut Marc', $content);
    }

    public function testGlobalParamters()
    {
        $this->renderer->addPath(__DIR__ . '/view');
        $this->renderer->addGlobal('nom', 'Marc');
        $content = $this->renderer->render('demoparams');
        $this->assertEquals('salut Marc', $content);
    }

}