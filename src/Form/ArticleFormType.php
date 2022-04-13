<?php

namespace App\Form;

use App\Form\Model\ArticleFormModel;
use Diplom\ArticleSubjectProviderBundle\ArticleSubjectProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    private ArticleSubjectProvider $subjectProvider;

    public function __construct(ArticleSubjectProvider $subjectProvider) {
        $this->subjectProvider = $subjectProvider;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $subjects = $this->subjectProvider->getAllSubjects();
        $subject_choices = [];
        foreach ($subjects as $subject) {
            $subject_choices[$subject->getName()] = $subject->getCode();
        }

        $builder
            ->add('subject', ChoiceType::class, [
                'label' => 'Тематика',
                'choices' => $subject_choices
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
                    'attr' => ['class' => 'email-box', 'placeholder' => 'Продвигаемое слово'],
                    'required' => false
                ],
                'prototype' => true,
                'allow_add' => true,
                'data' => [1 => '', 2 => ''],
            ])
            ->add('wordsCount', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'label' => 'Кол-во',
                    'attr' => ['class' => 'email-box', 'placeholder' => 'Кол-во'],
                    'required' => false
                ],
                'prototype' => true,
                'prototype_data' => 'New Tag Placeholder',
                'allow_add' => true,
                'data' => [1 => '', 2 => ''],
            ])
            ->add('images', FileType::class, [
                'label' => 'Изображения',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Выберите изображения',
                    'multiple' => true,
                ]
            ])
            ->add('submit', SubmitType::class, [
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