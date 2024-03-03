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
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-input mt-1 block w-full border-gray-300 rounded-md'],
                'label_attr' => ['class' => 'block text-sm font-semibold text-gray-700 mb-1']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-input mt-1 block w-full border-gray-300 rounded-md'],
                'label_attr' => ['class' => 'block text-sm font-semibold text-gray-700 mb-1']
            ])
            ->add('street', TextareaType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-input mt-1 block w-full border-gray-300 rounded-md'],
                'label_attr' => ['class' => 'block text-sm font-semibold text-gray-700 mb-1']
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'form-input mt-1 block w-1/2 border-gray-300 rounded-md'],
                'label_attr' => ['class' => 'block text-sm font-semibold text-gray-700 mb-1']
            ])
            ->add('zip_code', TextType::class, [
                'label' => 'Code postal',
                'attr' => ['class' => 'form-input mt-1 block w-1/4 border-gray-300 rounded-md'],
                'label_attr' => ['class' => 'block text-sm font-semibold text-gray-700 mb-1']
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'attr' => ['class' => 'form-input mt-1 block w-1/4 border-gray-300 rounded-md'],
                'label_attr' => ['class' => 'block text-sm font-semibold text-gray-700 mb-1']
            ]);
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
