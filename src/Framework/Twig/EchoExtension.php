<?php


namespace App\Framework\Twig;

use DateTime;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class EchoExtension extends AbstractExtension
{

    /**tte
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('echo', [$this, 'echo'], ['is_safe' => ['html']])
        ];
    }

    public function echo(string $content)
    {
        return "<di> $content</di>";
    }
}
