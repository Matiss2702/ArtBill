<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse email',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre prénom',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'options' => ['attr' => ['autocomplete' => 'new-password']],
                'first_options' => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Répéter le mot de passe',
                ],
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les conditions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('companyName', TextType::class, [
                'mapped' => false,
                'label' => 'Nom de l\'entreprise',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le nom de l\'entreprise',
                    ]),
                ],
            ])
            ->add('siren', IntegerType::class, [
                'mapped' => false,
                'label' => 'Numéro SIREN',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le numéro SIREN',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{9}$/',
                        'message' => 'Le numéro SIREN doit comporter exactement 9 chiffres',
                    ]),
                ],
            ])
            ->add('street', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Rue',
            ])
            ->add('city', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Ville',
            ])
            ->add('country', CountryType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Pays',
            ])
            ->add('vatNumber', IntegerType::class, [
                'mapped' => false,
                'label' => 'Numéro de TVA',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le numéro de TVA',
                    ]),
                ],
            ])
            ->add('shareCapital', IntegerType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Capital social',
            ])
            ->add('bankInformationStatement', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Relevé d\'information bancaire',
            ])
            ->add('zipCode', IntegerType::class, [
                'mapped' => false,
                'label' => 'Code postal',
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'exactMessage' => 'Le code postal doit comporter exactement {{ limit }} caractères',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
