<?php

namespace App\Form;

use App\Entity\About;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AboutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', ChoiceType::class, [
                'label' => 'Travail en entreprise ou scolaire',
                'choices'  => [
                    'Education' => "Education",
                    'Travail en Entreprise' => "Travail en Entreprise"
                ]
            ])
            ->add('work', TextType::class, [
                'label' => "Formation faite",
                "attr" => [
                    "placeholder" => "Formation que vous avez effectué",
                ]
            ])
            ->add('timeWork', ChoiceType::class, [
                'label' => 'Mode de travail en entreprise ou scolaire',
                'choices'  => [
                    'Plein temps' => "Plein temps",
                    'Temps partiel' => "Temps partiel"
                ]
                
            ])
            ->add('company', TextType::class, [
                'label' => "Nom de l'entrpise",
                "attr" => [
                    "placeholder" => "Nom de l'entrpise",
                ]
            ])
            ->add("city", TextType::class, [
                'label' => "Lieu de l'emploi ou de la formation scolaire",
                "attr" => [
                    "placeholder" => "Ville de la formation",
                ]
            ])
            ->add('firstDate', DateType::class, [
                'label' => "Début de la formation"
            ])

            ->add('lastDate', DateType::class, [
                'label' => "Fin de la formation"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => About::class
        ]);
    }
}
