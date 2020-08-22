<?php


namespace App\Shop\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NumberFormatExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new  TwigFunction('number_format', [$this, 'numberFormat'])

        ];
    }

    public function numberFormat($price)
    {
        return number_format($price, 2, '.', " ")." $";
    }
}
