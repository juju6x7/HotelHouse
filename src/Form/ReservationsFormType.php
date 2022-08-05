<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateArrival', DateType::class, [
                'label' => "Date d'arrivée",
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ])

            ->add('dateDeparture', DateType::class, [
                'label' => "Date de départ",
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut pas être vide."
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => "Votre prénom doit comporter {{ limit }} caractères minimum.",
                        'maxMessage' => "Votre prénom doit comporter {{ limit }} caractères maximum."
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut pas être vide."
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => "Votre nom doit comporter {{ limit }} caractères minimum.",
                        'maxMessage' => "Votre nom doit comporter {{ limit }} caractères maximum."
                    ]),
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut pas être vide."
                    ]),
                    new Length([
                        'min' => 9,
                        'max' => 30,
                        'minMessage' => "Votre nom doit comporter {{ limit }} caractères minimum.",
                        'maxMessage' => "Votre nom doit comporter {{ limit }} caractères maximum."
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'constraints' => [
                    new Email([
                        'message' => "Votre email n'est pas au bon format : mail@exemple.fr"
                    ]),
                    new NotBlank([
                        'message' => "Ce champ ne peut pas être vide."
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 180,
                        'minMessage' => "Votre email doit comporter {{ limit }} caractères minimum.",
                        'maxMessage' => "Votre email doit comporter {{ limit }} caractères maximum."
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'validate' => false,
                'attr' => [
                    'class' => 'd-block col-3 my-3 mx-auto btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
