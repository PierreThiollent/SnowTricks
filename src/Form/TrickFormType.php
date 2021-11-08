<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('images', FileType::class, [
                'multiple'    => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner au moins une image.',
                    ]),
                    new Count([
                        'max'        => 3,
                        'maxMessage' => 'Vous ne pouvez pas ajouter plus de 3 images.',
                    ]),
                    new All([
                        'constraints' => [
                            new Image([
                                'maxSize'        => '3M',
                                'maxSizeMessage' => 'La taille de chaque image ne doit pas dépasser 3MB.',
                            ]),
                        ]
                    ]),
                ],
            ])
            ->add('video', TextType::class)
            ->add('category', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'name',
                'placeholder'  => 'Catégorie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'validation_groups'
        ]);
    }
}
