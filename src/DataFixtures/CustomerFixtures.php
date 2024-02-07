<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Customer;

class CustomerFixtures extends Fixture
{
    public const CUSTOMER = 'customer_test';

    public function load(ObjectManager $manager): void
    {
        $customer = new Customer();
        $customer->setName('Customer Test ');
        $customer->setEmail("EmailTest@gmail.com");
        $customer->setStreet("Street Test");
        $customer->setCity("City Test");
        $customer->setZipCode("75000");
        $customer->setCountry("Country Test");
        
        $manager->persist($customer);
        $manager->flush();

        $this->addReference(self::CUSTOMER, $customer);
    }
}
