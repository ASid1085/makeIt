<?php

namespace App\Form;

use App\Entity\Step;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            /*->add('project', EntityType::class, [
                'class' => Project::class,
                'label' => 'Projet',
                'placeholder' => 'Choisissez votre projet',
                'autocomplete' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])*/
            ->add('name', TextType::class, [
                'attr' => [
                    'autofocus' => true,
                    'class' => 'form-control',
                ]
            ])
            ->add('number', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('category', ChoiceType::class, [
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
                'attr' => [
                    'rows' => 10,
                    'cols' => 50,
                    'class' => 'form-control',
                ]
            ])
            ->add('status', ChoiceType::class, [
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
        ]);
    }
}
