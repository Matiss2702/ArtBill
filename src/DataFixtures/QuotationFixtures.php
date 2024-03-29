<?php

namespace App\DataFixtures;

use App\Entity\Quotation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class QuotationFixtures extends Fixture implements DependentFixtureInterface

{
    public const QUOTATION = 'Devis';

    private $quotationStatus = ['created', 'sent', 'refused', 'accepted', 'paid', 'expired'];

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $quotation = new Quotation();
            $quotation->setDescription('description ' . $i);
            $quotation->setStatus($this->quotationStatus[array_rand($this->quotationStatus)]);
            $quotation->setDate(new \DateTime());  // Date de création
            $quotation->setOwner($this->getReference(UserFixtures::ADMIN_USER));
            $quotation->setCompany($this->getReference(CompanyFixtures::COMPANY_GRAPHIKART));
            $quotation->setCustomer($this->getReference(CustomerFixtures::CUSTOMER));
            $quotation->setVersion(0);

            $manager->persist($quotation);
        }
        $manager->flush();

        $this->addReference(self::QUOTATION, $quotation);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CustomerFixtures::class,
        ];
    }
}
