<?php

namespace App\Form;

use App\Form\Model\AnonymousArticleFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnonymousArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $title_attr = ['placeholder' => 'Заголовок статьи'];
        $word_attr = ['placeholder' => 'Продвигаемое слово'];
        $submit_attr = ['class' => 'btn btn-lg btn-primary btn-block text-uppercase'];
        if($options && isset($options['data'])) {
            $article = $options['data'];
            $title_attr['disabled'] = $word_attr['disabled'] = $submit_attr['disabled'] = true;
            $title_attr['value'] = $article->title;
            $word_attr['value'] = $article->word;
        }
        $builder
            ->add('title', null, [
                'label' => 'Заголовок статьи',
                'attr' => $title_attr,
            ])
            ->add('word', TextType::class, [
                'label' => 'Продвигаемое слово',
                'attr' => $word_attr,
            ])
            ->add('save', SubmitType::class, [
                'attr' => $submit_attr,
                'label' => 'Попробовать'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnonymousArticleFormModel::class,
        ]);
    }
}
