<?php


namespace App\Framework\Twig;

use App\Framework\Session\FlashService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FlashExtension extends AbstractExtension
{
    /**
     * @var FlashService
     */
    private $flashService;

    public function __construct(FlashService $flashService)
    {
        $this->flashService = $flashService;
    }

    public function getFunctions()
    {
        return [
            new  TwigFunction('flash', [$this, 'goFlash'])
        ];
    }

    public function goFlash($type)
    {
        return $this->flashService->get($type);
    }
}
