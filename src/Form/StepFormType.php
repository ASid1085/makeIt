<?php

namespace App\Form;

use App\Entity\Step;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StepFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'étape',
                'row_attr' => ['class' => 'mb-3'],
                'attr' => [
                    'autofocus' => true,
                    'class' => 'form-control',
                ]
            ])
            ->add('number', IntegerType::class, [
                'data' => $options['nb_steps_already_existing'],
                'row_attr' => [
                    'class' => 'visually-hidden'
                ],
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie de l\'étape',
                'row_attr' => ['class' => 'mb-3'],
                'choices' => [
                    'Specifications fonctionnelles' => 'Specifications fonctionnelles',
                    'Specifications techniques' => 'Specifications techniques',
                    'Conception' => 'Conception',
                    'developpement' => 'developpement',
                    'Revue de code' => 'Revue de code',
                    'Autres' => 'Autres',
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'étape',
                'row_attr' => ['class' => 'mb-3'],
                'attr' => [
                    'rows' => 10,
                    'cols' => 50,
                    'class' => 'form-control',
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label_attr' => ['class' => 'visually-hidden'],
                'row_attr' => ['class' => 'mb-3'],
                'attr' => [
                    'class' => 'form-check form-check-inline d-flex justify-content-evenly', 
                ],
                'choices' => [
                    'Effectuée ' => 'Effectuée',
                    'Testée ' => 'Testée',
                    'Validée ' => 'Validée',
                ],
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Step::class,
            'nb_steps_already_existing' => 0,
        ]);

        $resolver->setAllowedTypes('nb_steps_already_existing', ['int']);

    }
}
