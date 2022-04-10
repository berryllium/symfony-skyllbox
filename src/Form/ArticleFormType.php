<?php

namespace App\Form;

use App\Form\Model\ArticleFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', ChoiceType::class, [
                'label' => 'Тематика',
                'choices' => [
                    'PHP' => 'php',
                    'Гитары' => 'guitar'
                ]
            ])
            ->add('title', null, [
                'label' => 'Заголовок статьи',
                'attr' => [
                    'placeholder' => 'Заголовок статьи',
                ]
            ])
            ->add('keyword0', null, [
                'label' => 'Ключевое слово',
                'attr' => [
                    'placeholder' => 'Ключевое слово',
                ]
            ])
            ->add('keyword1', null, [
                'label' => 'Родительный падеж',
                'attr' => [
                    'placeholder' => 'Родительный падеж',
                ]
            ])
            ->add('keyword7', null, [
                'label' => 'Множественное число',
                'attr' => [
                    'placeholder' => 'Множественное число',
                ]
            ])
            ->add('sizeFrom', NumberType::class, [
                'label' => 'Размер статьи от',
                'attr' => [
                    'placeholder' => 'Размер статьи от',
                ]
            ])
            ->add('sizeTo', NumberType::class, [
                'label' => 'до',
                'attr' => [
                    'placeholder' => 'до',
                ]
            ])
            ->add('words', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'label' => 'Продвигаемое слово',
                    'attr' => ['class' => 'email-box'],
                ],
            ])
            ->add('words', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'label' => 'Продвигаемое слово',
                    'attr' => ['class' => 'email-box', 'placeholder' => 'Продвигаемое слово'],
                ],
                'prototype' => true,
                'prototype_data' => 'New Tag Placeholder',
                'allow_add' => true,
                'data' => [1 => '', 2 => ''],
            ])
            ->add('wordsCount', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'label' => 'Кол-во',
                    'attr' => ['class' => 'email-box', 'placeholder' => 'Кол-во'],
                ],
                'prototype' => true,
                'prototype_data' => 'New Tag Placeholder',
                'allow_add' => true,
                'data' => [1 => '', 2 => ''],
            ])
            ->add('images', FileType::class, [
                'label' => 'Изображения',
                'attr' => [
                    'placeholder' => 'Выберите изображения',
                    'multiple' => true
                ]
            ])
            ->add('submit', ButtonType::class, [
                'label' => 'Создать',
                'attr' => [
                    'class' => 'btn btn-lg btn-primary btn-block text-uppercase'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleFormModel::class
        ]);
    }
}
