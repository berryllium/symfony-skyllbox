<?php

namespace App\Form\Model;


use Symfony\Component\Validator\Constraints as Assert;

class ArticleFormModel
{
    /**
     * @Assert\NotBlank(message="Необходимо указать тематику")
     */
    public string $subject;
}
