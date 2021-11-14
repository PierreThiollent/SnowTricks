<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'first_options'   => [
                    'attr'        => [
                        'autocomplete' => 'new-password',
                        'class'        => 'appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-gray-500 focus:border-gray-500 focus:z-10 sm:text-sm',
                        'placeholder'  => '********'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez renseigner un mot de passe.',
                        ]),
                        new Length([
                            'min'        => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères',
                            'maxMessage' => 'Votre mot de passe peut contenir au maximum {{ limit }} caractères.',
                            // max length allowed by Symfony for security reasons
                            'max'        => 100,
                        ]),
                    ],
                ],
                'second_options'  => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class'        => 'appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-gray-500 focus:border-gray-500 focus:z-10 sm:text-sm',
                        'placeholder'  => '********'
                    ],
                ],
                'invalid_message' => 'Les deux mots de passe ne correspondent pas.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped'          => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
