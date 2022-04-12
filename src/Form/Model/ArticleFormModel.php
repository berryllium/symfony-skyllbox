<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ArticleFormModel
{
    /**
     * @Assert\NotBlank(message="Необходимо указать тематику")
     */
    public string $subject;


    public string $title;
    public string $keyword0;
    public string $keyword1;
    public string $keyword7;
    public string $sizeFrom;
    public string $sizeTo;
    public array $words;
    public array $wordsCount;
    public string $images;
}
