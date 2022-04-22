<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Ваше имя',
                'attr' => ['placeholder' => 'Ваше имя'],
                'constraints' => [
                    new NotNull([
                        'message' => 'Это обязательное поле',
                    ])
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Ваш Email',
                'attr' => ['placeholder' => 'Ваш Email'],
                'constraints' => [
                    new NotNull([
                        'message' => 'Это обязательное поле',
                    ]),
                    new Email([
                        'message' => 'Введите корректный email',
                    ]),
                ],
            ])
            ->add('pass', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Введенные пароли не совпадают',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options'  => ['label' => 'Пароль', 'attr' => ['placeholder' => 'Пароль']],
                'second_options' => ['label' => 'Подтверждение пароля', 'attr' => ['placeholder' => 'Подтверждение пароля']],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Длина не менее {{ limit }} символов',
                    ]),
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
