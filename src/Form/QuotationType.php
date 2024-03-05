<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Quotation;
use App\Entity\Service;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityRepository;


class QuotationType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();
        $company = $user->getCompany();

        $builder
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_combine(Quotation::QUOTATION_STATUS, Quotation::QUOTATION_STATUS)
            ])
            ->add('date') 
            ->add('dueDate')
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'email',
                'query_builder' => function(EntityRepository $er) use ($company) {
                    return $er->createQueryBuilder('c')
                        ->where('c.company = :company')
                        ->setParameter('company', $company);
                },
                'required' => false, 
                'placeholder' => '--',
                'data' => $options['data']->getCustomer() ?? null,
            ])
            ->add('services', CollectionType::class, [
                'entry_type' => ServiceType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quotation::class,
        ]);
    }
}

