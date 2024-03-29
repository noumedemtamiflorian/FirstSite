<?php


namespace App\Framework\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TextExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]|void
     */
    public function getFilters() : array
    {
        return [
            new TwigFilter('excerpt', [$this, 'excerpt'])
        ];
    }

    /**
     *
     * renvoi un extrait du contenue
     *
     * @param string $content
     * @param int $maxLength
     * @return string
     */
    public function excerpt(string $content, int $maxLength = 100): string
    {
        if (mb_strlen($content) > $maxLength) {
            $excerpt = substr($content, 0, $maxLength);
            $lastSpace = strrpos($excerpt, ' ');
            return substr($excerpt, 0, $lastSpace) . '  ...';
        }
        return $content;
    }
}
