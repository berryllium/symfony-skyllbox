<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('morph', [$this, 'formatMorph'], ['needs_context' => true])
        ];
    }

    /**
     * @param array $set падежи русского языка (индексы 0 - 5) и множественное число (индекс 6)
     * @return string
     */
    public function formatMorph($context, string $word, int $case)
    {
        $keywords = $context['keywords'];
        return $keywords[$case] ?? $word;
    }
}