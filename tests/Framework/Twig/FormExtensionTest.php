<?php


namespace Tests\Framework\Twig;


use App\Framework\Twig\FormExtension;
use PHPUnit\Framework\TestCase;

class FormExtensionTest extends TestCase
{
    /**
     * @var FormExtension
     */
    private $formExtension;

    public function setUp(): void
    {
        $this->formExtension = new FormExtension();
    }

    private function trim(string $string)
    {
        $lines = explode('>', $string);
        $lines = array_map('trim', $lines);
        return implode(' ', $lines);
    }

    public function assertSimilar(string $expected, string $actual)
    {
        $this->assertEquals($this->trim($expected), $this->trim($actual));
    }

    public function testField()
    {
        $html = $this->formExtension->flied([], 'name', 'demo', 'Titre');
        $this->assertSimilar("
<div class=\"form-group\">
   <label for=\"name\">Titre</label>
    <input type=\"text\" class=\"form-control\" id=\"name\" name=\"name\" value=\"demo\">
</div>
", $html);
    }

    public function testTextarea()
    {
        $html = $this->formExtension->flied([], 'content', 'demo', 'Contenu', ['type' => 'textarea']);
        $this->assertSimilar("
<div class=\"form-group\">
    <label for=\"content\">Contenu</label>
    <textarea class=\"form-control\" id=\"content\" name=\"content\" cols=\"30\" rows=\"10\">demo</textarea>
</div>
", $html);
    }

    public function testFieldWithErrors()
    {
        $context = ['errors' => ['name' => 'erreur']];
        $html = $this->formExtension->flied($context, 'name', 'demo', 'Titre');
        $this->assertSimilar("
<div class=\"form-group has-danger\">
   <label for=\"name\">Titre</label>
    <input type=\"text\" class=\"form-control is-invalid form-control-danger\" id=\"name\" name=\"name\" value=\"demo\">
         <small class='form-text text-danger'>erreur</small>
</div>
", $html);
    }

    public function testFieldWithClass()
    {
        $html = $this->formExtension->flied(
            [],
            'name',
            'demo'
            , 'Titre',
            ['class' => 'demo']
        );
        $this->assertSimilar("
<div class=\"form-group\">
   <label for=\"name\">Titre</label>
    <input type=\"text\" class=\"form-control demo\" id=\"name\" name=\"name\" value=\"demo\">
</div>
", $html);
    }

    public function testSelect()
    {
        $html = $this->formExtension->flied(
            [],
            'name',
            2,
            'Titre',
            ['options' => [1 => 'Demo1', 2 => "Demo2"]]
        );
        $this->assertSimilar('
          <div class="form-group">
             <label for="name">Titre</label>
             <select  class="form-control" id="name" name="name">
                 <option value="1">Demo1</option>
                 <option value="2" selected>Demo2</option>
             </select>
          </div>', $html);
    }

    public function testEmail()
    {
        $html = $this->formExtension->flied(
            [],
            'email',
            "noumedemtamiflorian@gmail.com",
            'Votre Email',
            ["type" => "email"]
        );
        $this->assertSimilar("<div class=\"form-group\"> 
<label> for=\"email\" Votre Email</label> 
<input type=\"email\" class=\"form-control\" id=\"email\" name=\"email\" value=\"noumedemtamiflorian@gmail.com\" </div>"
            , $html);
    }

    public function testPassword()
    {
        $html = $this->formExtension->flied(
            [],
            'password',
            "noumedemtamiflorian@gmail.com",
            'Mots de passe',
            ["type" => "password"]
        );
        $this->assertSimilar("<div class=\"form-group\"> 
<label> for=\"password\" Mots de passe</label> 
<input type=\"password\" class=\"form-control\" id=\"password\" name=\"password\" value=\"noumedemtamiflorian@gmail.com\" </div>"
            , $html);
    }
}
