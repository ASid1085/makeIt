<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                //'mapped' => false,
                'label' => 'Fichier JPG ou PNG',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Delete ?',
                'download_uri' => false,
                'imagine_pattern' => 'squared_thumbnail_small',
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom du projet',
                'attr' => [
                    'autofocus' => true,
                    'class' => 'form-control'
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de projet',
                'choices' => [
                    'Développement de logiciels' => 'Développement de logiciels',
                    'Informatique et télécom' => 'Informatique et télécom',
                    'Ingénierie et construction' => 'Ingénierie et construction',
                    'Services commerciaux et financiers' => 'Services commerciaux et financiers',
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            /*->add('collaborateurs', EntityType::class, [
                'class' => User::class,
                'label' => 'Choisissez vos collaborateurs',
                'autocomplete' => true,
                'multiple' => true,
                'query_builder' => function (UserRepository $ur) {
                    return $ur->createQueryBuilder('user');
                },
                'row_attr' => ['class' => 'mb-5'],
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
