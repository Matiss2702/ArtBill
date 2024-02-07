<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public const COMPANY_GRAPHIKART = 'Graphikart';

    public function load(ObjectManager $manager): void
    {
        $company = new Company();
        $company->setCommercialName('Graphikart');
        $company->setSiren(123456789);
        $company->setTvaNumber(100200300);
        $company->setShareCapital(100000);
        $manager->persist($company);
        $manager->flush();

        $this->addReference(self::COMPANY_GRAPHIKART, $company);
    }
}
