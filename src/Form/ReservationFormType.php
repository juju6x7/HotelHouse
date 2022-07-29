<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateArrival')
            ->add('dateDeparture')
            ->add('priceTotal')
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('email')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deletedAt')
            ->add('chambre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
