<?php

namespace Tests\Framework;
require dirname(dirname(__DIR__)) . "/vendor/autoload.php";

use Framework\PHPRenderer;
use PHPUnit\Framework\TestCase;


class PHPRendererTest extends TestCase
{

    /**
     * @var PHPRenderer
     */
    private $renderer;

    public function setUp(): void
    {
        $this->renderer = new PHPRenderer(__DIR__ . '/views');
    }

    public function testRendererTheRightPath()
    {
        $content = $this->renderer->render('@blog/demo');
        $this->assertEquals('salut les gens', $content);
    }

    public function testRendererTheDefaultPath()
    {
        $content = $this->renderer->render('demo');
        $this->assertEquals('salut les gens', $content);
    }

    public function testRendererWithParams()
    {
        $content = $this->renderer->render('demoparams', ['nom' => 'Marc']);
        $this->assertEquals('salut Marc', $content);
    }

    public function testGlobalParamters()
    {
        $this->renderer->addGlobal('nom', 'Marc');
        $content = $this->renderer->render('demoparams');
        $this->assertEquals('salut Marc', $content);
    }

}