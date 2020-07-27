<?php

namespace Tests\Framework\Twig;


use App\Framework\Twig\TextEntension;
use App\Framework\Twig\TextExtension;
use PHPUnit\Framework\TestCase;

class TextExtensionTest extends TestCase
{

    /**
     * @var TextExtension
     */
    private $textExtension;

    public function setUp(): void
    {
        $this->textExtension = new  TextExtension();
    }

    public function testExceprtWithShortText()
    {
        $text = "Salut";
        $result = $this->textExtension->excerpt($text, 10);
        $this->assertEquals($text, $result);
    }

    public function testExceprtWithLongText()
    {
        $text = "Salut je suis la et toi";
        $result = $this->textExtension->excerpt($text, 10);
        $this->assertEquals("Salut je  ...", $result);
    }
}