<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProjectType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom',
            "attr" => [
                "placeholder" => "Nom du projet",
            ]

        ])

        ->add('brochure', FileType::class, [
            'label' => 'Image du projet',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '100M',
                    'mimeTypes' => [
                        'image/svg+xml',
                        'image/png',
                        'image/jpeg',
                        'image/gif',
                    ],
                    'mimeTypesMessage' => "Merci d'ajouter une image valide",
                ])
            ],
        ])

        ->add('link', TextType::class, [
            'label' => 'Lien git',
            'required' => false,
            "attr" => [
                "placeholder" => "Lien git du projet",
            ]
        ])

        ->add('description', TextareaType::class, [
            "attr" => [
                "placeholder" => "Description du projet",
            ]
            ])

        ->add('date', DateType::class)

        ->add('skills');

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class
        ]);
    }
}