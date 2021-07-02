<?php
namespace App\Extension\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('isScheduleMenuEnabled', [RuntimeScheduleMenu::class, 'isEnabled'])
        ];
    }
}