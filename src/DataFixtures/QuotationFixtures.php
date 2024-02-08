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
        $quotation = new Quotation();
        $quotation->setDescription('description ');
        $quotation->setStatus($this->quotationStatus[array_rand($this->quotationStatus)]);
        $quotation->setDate(new \DateTime());  // Date de création
        $quotation->setOwner($this->getReference(UserFixtures::ADMIN_USER));
        $quotation->setCompany($this->getReference(CompanyFixtures::COMPANY_GRAPHIKART));
        $manager->persist($quotation);

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
