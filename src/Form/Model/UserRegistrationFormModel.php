<?php

namespace App\Form\Model;

use App\Validator\RegistrationSpam;
use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="Необходимо указать email")
     * @Assert\Email()
     * @UniqueUser()
     */
    public string $email;

    /**
     * @Assert\NotBlank(message="Необходимо указать имя")
     */
    public string $firstName;

    /**
     * @Assert\NotBlank (message="Пароль не указан")
     * @Assert\Length(min=6, minMessage="Длина пароля должна быть не менее 6 символов")
     */
    public string $plainPassword;
}