<?php

namespace App\Twig;

use Diplom\ArticleSubjectProviderBundle\ArticleSubjectProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private ArticleSubjectProvider $subjectProvider;

    public function __construct(ArticleSubjectProvider $subjectProvider) {

        $this->subjectProvider = $subjectProvider;
    }
    public function getFilters()
    {
        return [
            new TwigFilter('morph', [$this, 'formatMorph'], ['needs_context' => true]),
            new TwigFilter('paragraph', [$this, 'paragraph'], ['needs_context' => true]),
            new TwigFilter('paragraphs', [$this, 'paragraphs'], ['needs_context' => true]),
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