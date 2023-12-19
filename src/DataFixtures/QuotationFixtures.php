<?php

namespace App\DataFixtures;

use App\Entity\Quotation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class QuotationFixtures extends Fixture implements DependentFixtureInterface

{
    private $quotationStatus = ['created', 'sent', 'refused', 'accepted', 'paid', 'expired'];

    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 10; $i++) {
            $quotation = new Quotation();
            $quotation->setDescription('description ' . $i);
            $quotation->setAmountHt(mt_rand(50, 200));  // Montant HT aléatoire entre 50 et 200
            $quotation->setAmountTtc($quotation->getAmountHt() * 1.2);  // Montant TTC basé sur le HT
            $quotation->setQuantity(mt_rand(1, 5));  // Quantité aléatoire entre 1 et 5
            $quotation->setStatus($this->quotationStatus[array_rand($this->quotationStatus)]);
            $quotation->setDate(new \DateTime());  // Date de création
            $quotation->setOwner($this->getReference(UserFixtures::ADMIN_USER));
            $quotation->setCompany($this->getReference(CompanyFixtures::COMPANY_GRAPHIKART));

            $manager->persist($quotation);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class, CompanyFixtures::class
        ];
    }
}
