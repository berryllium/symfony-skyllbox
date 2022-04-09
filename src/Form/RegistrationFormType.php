<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistrationFormType extends AbstractType
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
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Пароль',
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Пароль'],
                'error_bubbling'=>false,
                'constraints' => [
                    new NotNull([
                        'message' => 'Пожалуйста введите пароль',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Длина пароля должна быть не менее {{ limit }} символов',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Подтверждение пароля',
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Подтверждение пароля'],
                'constraints' => [
                    new NotNull([
                        'message' => 'Пожалуйста введите пароль',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
