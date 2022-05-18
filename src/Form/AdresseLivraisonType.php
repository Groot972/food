<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseLivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', null,  array(
                'label' => 'Adresse',
                'attr' => array(
                    'placeholder' => 'Rue Victor-Hugo'
                )
            ))
            ->add('complement', null,  array(
                'label' => "Complément d'adresse",
                'attr' => array(
                    'placeholder' => 'Apartement, Batiment'
                )
            ))
            ->add('codePostal', null,  array(
                'label' => 'Code Postal',
                'attr' => array(
                    'placeholder' => '75000'
                )
            ))
            ->add('ville', null,  array(
                'label' => 'Ville',
                'attr' => array(
                    'placeholder' => 'Paris'
                )
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
