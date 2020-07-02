<?php

namespace Test\Framework;
require dirname(dirname(__DIR__)) . "/vendor/autoload.php";

use App\Framework\Validator;
use function DI\string;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function makeValidator(array $params)
    {
        return new Validator($params);
    }

    public function testRequiredIfFail()
    {
        $errors = $this->makeValidator(['name' => 'joe'])
            ->required('name', 'content')
            ->getErrors();
        $this->assertCount(1, $errors);
    }

    public function testNotEmpty()
    {
        $errors = $this->makeValidator(['name' => 'joe', 'content' => ''])
            ->notEmpty('content')
            ->getErrors();
        $this->assertCount(1, $errors);

    }

    public function testRequiredIfSuccess()
    {
        $errors = $this->makeValidator(['name' => 'joe', 'content' => 'content'])
            ->required('name', 'content')
            ->getErrors();
        $this->assertCount(0, $errors);
    }

    public function testSlugSuccess()
    {
        $errors = $this->makeValidator(['slug' => 'aze-aze-azeaze34'])
            ->slug('slug')
            ->getErrors();
        $this->assertCount(0, $errors);

    }

    public function testSlugError()
    {
        $errors = $this->makeValidator([
            'slug' => 'aze-aze-azeAze34',
            'slug2' => 'aze-aze-azeAze34',
            'slug3' => 'aze--aze-aze'
        ])
            ->slug('slug')
            ->slug('slug2')
            ->slug('slug3')
            ->slug('slug4')
            ->getErrors();
        $this->assertCount(3, $errors);

    }

    public function testCountErrorsLength()
    {
        $params = ['slug' => '123456789'];
        $errors = $this->makeValidator($params);
        $this->assertCount(0, $errors->length('slug', 3)->getErrors());
        $this->assertCount(1, $errors->length('slug', 12)->getErrors());
        $this->assertCount(1, $errors->length('slug', 3, 4)->getErrors());
        $this->assertCount(1, $errors->length('slug', 3, 20)->getErrors());
        $this->assertCount(1, $errors->length('slug', null, 20)->getErrors());
        $this->assertCount(1, $errors->length('slug', null, 9)->getErrors());
    }

    public function testMessageErrorsLength()
    {
        $params = ['slug' => '123456789'];
        $errors = $this->makeValidator($params)->length('slug', 100)->getErrors();
        $this->assertEquals('Le champs slug doit contenir plus de 100 caracteres', $errors['slug']);
        $errors = $this->makeValidator($params)->length('slug', null, 6)->getErrors();
        $this->assertEquals('Le champs slug doit contenir moins de  6 caracteres', $errors['slug']);
        $errors = $this->makeValidator($params)->length('slug', 3, 5)->getErrors();
        $this->assertEquals('Le champs slug doit etre entre 3 et 5 caracteres', $errors['slug']);
    }

    public function testDateTime()
    {
        $this->assertCount(0,$this->makeValidator(['date' => '2012-12-12 11:12:13'])->dateTime('date')->getErrors());
        $this->assertCount(0,$this->makeValidator(['date' => '2012-12-12 00:00:00'])->dateTime('date')->getErrors());
        $this->assertCount(1,$this->makeValidator(['date' => '2012-21-12'])->dateTime('date')->getErrors());
        $this->assertCount(0,$this->makeValidator(['date' => '2013-02-28 11:12:13'])->dateTime('date')->getErrors());
        $this->assertCount(0,$this->makeValidator(['date' => '2013-12-11 11:12:13'])->dateTime('date')->getErrors());
    }
}