<?php

namespace App\Twig;

use App\Service\Morpher;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('morph', [$this, 'formatMorph'])
        ];
    }

    /**
     * @param array $set падежи русского языка (индексы 0 - 5) и множественное число (индекс 6)
     * @return string
     */
    public function formatMorph(string $word, int $case)
    {
        return Morpher::getInstance()->get($word, $case);
    }
}