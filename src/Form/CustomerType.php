<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
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
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ], $defaultFieldOptions)
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ], $defaultFieldOptions)
            ->add('street', TextType::class, [
                'label' => 'Adresse',
            ], $defaultFieldOptions)
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ], $defaultFieldOptions)
            ->add('zip_code', TextType::class, [
                'label' => 'Code postal',
            ], $defaultFieldOptions)
            ->add('country', TextType::class, [
                'label' => 'Pays',
            ], $defaultFieldOptions);
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
