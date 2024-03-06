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
        $defaultFieldOptions = [
            'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
            'attr' => [
                'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5',
                'style' => 'padding: 10px;'
            ],
            'error_bubbling' => true,
        ];

        $builder
            ->add('email', EmailType::class, array_merge([
                'label' => 'Email',
                'constraints' => [new NotBlank(['message' => 'Veuillez saisir une adresse email'])],
            ], $defaultFieldOptions))
            ->add('firstname', TextType::class, array_merge([
                'label' => 'Prénom',
                'constraints' => [new NotBlank(['message' => 'Veuillez saisir votre prénom'])],
            ], $defaultFieldOptions))
            ->add('lastname', TextType::class, array_merge([
                'label' => 'Nom',
                'constraints' => [new NotBlank(['message' => 'Veuillez saisir votre nom'])],
            ], $defaultFieldOptions))
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'options' => ['attr' => ['autocomplete' => 'new-password']],
                'first_options' => array_merge([
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank(['message' => 'Veuillez saisir un mot de passe']), 
                        new Length([
                            'min' => 6,
                            'max' => 4096,
                            'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères',
                            'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères',
                        ]),
                    ],
                ], $defaultFieldOptions),
                'second_options' => array_merge([
                    'label' => 'Répéter le mot de passe',
                ], $defaultFieldOptions),
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
            ])            
            ->add('RGPDConsent', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les conditions',
                'constraints' => [new IsTrue(['message' => 'Vous devez accepter nos conditions.'])],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
            ])
            ->add('companyName', TextType::class, array_merge([
                'mapped' => false,
                'label' => 'Nom de l\'entreprise',
                'constraints' => [new NotBlank(['message' => 'Veuillez saisir le nom de l\'entreprise'])],
            ], $defaultFieldOptions))
            ->add('siren', TextType::class, array_merge([
                'mapped' => false,
                'label' => 'Numéro SIREN',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le numéro SIREN']),
                    new Regex(['pattern' => '/^\d+$/', 'message' => 'Le numéro SIREN doit contenir uniquement des chiffres']),
                ],
                'error_bubbling' => true,
            ], $defaultFieldOptions))
            ->add('street', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Rue',
            ] + $defaultFieldOptions)
            ->add('city', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Ville',
            ] + $defaultFieldOptions)
            ->add('country', CountryType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Pays',
            ] + $defaultFieldOptions)
            ->add('vatNumber', TextType::class, array_merge([
                'mapped' => false,
                'label' => 'Numéro de TVA',
                'constraints' => [new NotBlank(['message' => 'Veuillez saisir le numéro de TVA'])],
            ], $defaultFieldOptions))
            ->add('shareCapital', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Capital social',
            ] + $defaultFieldOptions + ['constraints' => [
                new Regex(['pattern' => '/^\d+$/', 'message' => 'Le capital social doit contenir uniquement des chiffres']),
            ]])
            ->add('bankInformationStatement', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Relevé d\'information bancaire',
            ] + $defaultFieldOptions)
            ->add('zipCode', TextType::class, array_merge([
                'mapped' => false,
                'label' => 'Code postal',
                'constraints' => [
                    new Length(['min' => 5, 'max' => 5, 'exactMessage' => 'Le code postal doit comporter exactement {{ limit }} caractères']),
                    new Regex(['pattern' => '/^\d+$/', 'message' => 'Le code postal doit contenir uniquement des chiffres']),
                ],
            ], $defaultFieldOptions));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
