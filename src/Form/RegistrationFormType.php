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
                        'message' => 'Please enter an email',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'options' => ['attr' => ['autocomplete' => 'new-password']],
                'first_options' => [
                    'label' => 'Password',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'The password fields must match.',
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accept Terms',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('companyName', TextType::class, [
                'mapped' => false,
                'label' => 'Company Name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the company name',
                    ]),
                ],
            ])
            ->add('siren', IntegerType::class, [
                'mapped' => false,
                'label' => 'SIREN Number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the SIREN number',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{9}$/',
                        'message' => 'The SIREN number must be exactly 9 digits long',
                    ]),
                ],
            ])
            ->add('street', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Street',
            ])
            ->add('city', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'City',
            ])
            ->add('country', CountryType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Country',
            ])
            ->add('vatNumber', IntegerType::class, [
                'mapped' => false,
                'label' => 'VAT Number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the VAT number',
                    ]),
                ],
            ])
            ->add('shareCapital', IntegerType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Share Capital',
            ])
            ->add('bankInformationStatement', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Bank Information Statement',
            ])
            ->add('zipCode', IntegerType::class, [
                'mapped' => false,
                'label' => 'Zip Code',
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
