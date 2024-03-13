<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', null, [
                'attr' => [
                    'class' => 'w-full label-presta',
                ],
                'label' => 'Libellé',
             ])
            ->add('description', null, [
                'attr' => [
                    'class' => 'w-full description-presta'
                ]
            ])
            ->add('price', null, [
                'attr' => [
                    'step' => 0.01,
                    'class' => 'w-full price-presta'
                ],
                'label' => 'Prix',

            ])
            ->add('quantity', null, [
                'attr' => [
                    'class' => 'w-full quantity-presta'
                ],
                'label' => 'Quantité',
            ])
            ->add('vatRate', ChoiceType::class, [
                'choices' => array_combine(["0%", "10%", "20%"], Service::VAT_RATES),
                'attr' => [
                    'class' => 'w-full vat-presta'
                ],
                'label' => 'Taux de TVA',

            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'attr' => [
                    'class' => 'w-full category-presta'
                ],
                'label' => 'Catégories',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
