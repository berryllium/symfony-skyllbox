<?php

namespace App\Form\Model;


use Symfony\Component\Validator\Constraints as Assert;

class AnonymousArticleFormModel
{
    /**
     * @Assert\NotBlank(message="Необходимо указать заголовок")
     */
    public string $title;

    /**
     * @Assert\NotBlank(message="Необходимо указать ключевое слово")
     */
    public string $word;

}
