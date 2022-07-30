<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminCommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'help' => "* min caractères : 8,
                           * max caractères: 255,
                           * au moins 1 caractère spécial,
                           * au moins 1 majuscule,
                           * au moins 1 minuscule,
                           * au moins 1 chiffre"
            ])

            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne peut pas être vide."
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 255,
                        'minMessage' => "Votre mot de passe doit comporter {{ limit }} caractères minimum.",
                        'maxMessage' => "Votre mot de passe doit comporter {{ limit }} caractères maximum."
                    ]),
                ],
            ])

            ->add('pseudo', TextType::class, [
                'label' => "Pseudo"
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
            
            ->add('civility', ChoiceType::class, [
                'label' => 'Civilité',
                'expanded' => true,
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme'
                ],
                'choice_attr' => [
                    "Homme" => ['selected']
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Vous devez sélectionner une réponse."
                    ]),
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'validate' => false,
                'attr' => [
                    'class' => 'd-block col-3 my-3 mx-auto btn btn-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
