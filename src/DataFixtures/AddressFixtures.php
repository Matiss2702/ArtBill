<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public const ADDRESS_GRAPHIKART = 'graphikart';

    public function load(ObjectManager $manager): void
    {
        $address = new Address();
        $address->setStreet('10 rue de la paix');
        $address->setCity('Paris');
        $address->setZipCode(75000);
        $address->setCountry('France');



        $manager->persist($address);
        $manager->flush();

        $this->addReference(self::ADDRESS_GRAPHIKART, $address);
    }
}
