<?php


namespace App\Framework\Twig;

use DateTime;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeExtension extends AbstractExtension
{

    /**tte
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('ago', [$this, 'ago'], ['is_safe' => ['html']])
        ];
    }

    public function ago( $date, string $format = 'd/m/Y H:i')
    {
        $date = new DateTime($date);
        return '<time class="timeago" datetime="' . $date->format(DateTime::ISO8601) . '"> ' . $date->format($format) . '</time>';
    }
}
