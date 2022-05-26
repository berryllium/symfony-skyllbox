<?php

namespace App\Form\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public string $keyword2;
    public string $keyword3;
    public string $keyword4;
    public string $keyword5;
    public string $keyword6;
    public string $sizeFrom;
    public string $sizeTo;
    public array $words;
    public array $wordsCount;
    /**
     * @var UploadedFile[] $images
     */
    public array $images;

    public function getKeywords() {
        $keywords = [];
        foreach ($this as $k => $v) {
            if(strpos($k, 'keyword') === 0) {
                $i = (int) substr($k, 7);
                $keywords[$i] = $v;
            }
        }
        return $keywords;
    }
}
