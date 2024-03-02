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

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Les champs pour l'utilisateur...
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an email',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'options' => ['attr' => ['autocomplete' => 'password']],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'password',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'The password fields must match.',
                // Enable/disable CSRF protection for this form
                'csrf_protection' => true,
                // the name of the hidden HTML field that stores the token
                'csrf_field_name' => '_token',
                // an arbitrary string used to generate the value of the token
                'csrf_token_id'   => 'authenticate',
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label' => 'Accepter les termes',
            ])
            // Les champs pour la création de l'entreprise...
            ->add('companyName', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the company name',
                    ]),
                ],
            ])
            ->add('siren', TextType::class, [
                'mapped' => false,
                'required' => false, // Changez en true si nécessaire
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the SIREN number',
                    ]),
                    new Length([
                        'min' => 9,
                        'max' => 9,
                    ]),
                ],
            ])
            ->add('street', TextType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('country', CountryType::class, [
                'mapped' => false,
                'required' => false,
            ])
            // ->add('name', TextType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Please enter the company name',
            //         ]),
            //     ],
            // ])
            ->add('vatNumber', IntegerType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the VAT number',
                    ]),
                ],
            ])
            ->add('shareCapital', IntegerType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('bankInformationStatement', TextareaType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('zipCode', IntegerType::class, [
                'mapped' => false,
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'exactMessage' => 'The zip code must be exactly {{ limit }} characters long',
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
