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
        $company->setName('Graphikart');
        $company->setSiren(123456789);
        $company->setVatNumber(100200300);
        $company->setShareCapital(100000);
        $company->setZipCode(94000);

        $manager->persist($company);
        $manager->flush();

        $this->addReference(self::COMPANY_GRAPHIKART, $company);
    }
}
