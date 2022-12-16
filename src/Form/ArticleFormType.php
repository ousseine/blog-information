<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Titre']
            ])
            ->add('categories', null, [
                'label' => false,
                'attr' => ['placeholder' => 'Catégorie']
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['placeholder' => 'Image à la une']
            ])
            ->add('summary', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Sommaire',
                    'rows' => 5
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Contenu',
                    'rows' => 10
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class
        ]);
    }
}