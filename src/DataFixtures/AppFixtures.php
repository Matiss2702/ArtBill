<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Quotation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            // $quotation = new Quotation();
            // $quotation->setDescription('description ' . $i);
            // $quotation->setAmountHt(mt_rand(50, 200));  // Montant HT aléatoire entre 50 et 200
            // $quotation->setAmountTtc($quotation->getAmountHt() * 1.2);  // Montant TTC basé sur le HT
            // $quotation->setQuantity(mt_rand(1, 5));  // Quantité aléatoire entre 1 et 5
            // $quotation->setStatus(rand(0, 1) ? 'paid' : 'unpaid');  // Statut aléatoire entre 'paid' et 'unpaid'
            // $quotation->setDate(new \DateTimeImmutable());  // Date de création


            // $manager->persist($quotation);

            $user = new User();
            $user->setEmail('user' . $i . '@example.com');
            $user->setPassword(password_hash('password' . $i, PASSWORD_BCRYPT));  // Génération d'un mot de passe sécurisé
            $user->setRoles(['ROLE_USER']);
            $user->setFirstName("UserFirstName" . $i);
            $user->setLastName("UserLastName" . $i);
            // $user->addQuotation($quotation);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
