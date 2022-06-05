<?php

namespace App\Service\Subject;

use Diplom\ArticleSubjectProviderBundle\ArticleSubjectInterface;

class AbstractSubject implements ArticleSubjectInterface
{
    protected string $name;
    protected string $code;
    protected array $titles;
    protected array $descriptions;
    protected array $paragraphs;

    protected function getItems($itemName, $count) {
        if($count < 1) $count = 1;
        $result = [];
        while (count($result) < $count) {
            $key = array_rand($this->$itemName);
            $result[] = $this->$itemName[$key];
        }

        return $result;
    }

    public function getTitles($count = 1): array
    {
        return $this->getItems('titles', $count);
    }


    public function getTitle() :string {
        $titles = $this->getTitles();
        return array_pop($titles);
    }

    public function getDescriptions($count = 1) :array {
        return $this->getItems('descriptions', $count);
    }

    public function getDescription() :string {
        $descriptions = $this->getDescriptions();
        return array_pop($descriptions);
    }

    public function getParagraphs(int $count): array
    {
        return $this->getItems('paragraphs', $count);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}