<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const ADMIN_USER = 'Jan';

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@artbill.com');
        $user->setPassword(password_hash('artbill', PASSWORD_BCRYPT));
        $user->setRoles(['admin']);
        $user->setFirstName("Super");
        $user->setLastName("User");
        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::ADMIN_USER, $user);
    }
}
