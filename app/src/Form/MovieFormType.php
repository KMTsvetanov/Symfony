<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'class1 class2',
                    'placeholder' => 'Enter Title...',
                ],
                'label' => false,
                'required' => false,
            ])
            ->add('releaseYear', IntegerType::class, [
                'attr' => [
                    'class' => 'class3 class4',
                    'placeholder' => 'Enter Release Year...',
                ],
                'label' => false,
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'class5 class6',
                    'placeholder' => 'Enter Description...',
                ],
                'label' => false,
                'required' => false,
            ])
            ->add('imagePath', FileType::class, [
                'mapped' => $options['imagePathMapped'],
                'attr' => [
                    'class' => 'class7 class8',
                ],
                'label' => false,
                'required' => false,
            ])
//            ->add('actors')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
            'imagePathMapped' => true,
        ]);
    }
}
