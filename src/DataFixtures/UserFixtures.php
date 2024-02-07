<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADMIN_USER = 'Jan';

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@artbill.com');
        $user->setPassword(password_hash('artbill', PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setFirstName("Super");
        $user->setLastName("User");
        $user->setCompany($this->getReference(CompanyFixtures::COMPANY_GRAPHIKART));
        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::ADMIN_USER, $user);
    }
    public function getDependencies()
    {
        return [
            CompanyFixtures::class,
        ];
    }
}
