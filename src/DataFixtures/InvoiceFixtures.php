<?php

namespace App\DataFixtures;

use App\Entity\Invoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InvoiceFixtures extends Fixture implements DependentFixtureInterface
{
    private $invoiceStatus = ['created', 'sent', 'refused', 'accepted', 'paid', 'expired'];

    public function load(ObjectManager $manager): void
    {
        $invoice = new Invoice();
        $invoice->setCompany($this->getReference(CompanyFixtures::COMPANY_GRAPHIKART));
        $invoice->setStatus($this->invoiceStatus[array_rand($this->invoiceStatus)]);
        $invoice->setOwner($this->getReference(UserFixtures::ADMIN_USER));
        $invoice->setQuotations($this->getReference(QuotationFixtures::QUOTATION));
        $invoice->setDate(new \DateTime());
        $invoice->setDueDate(new \DateTime());
        $manager->persist($invoice);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class, CompanyFixtures::class, QuotationFixtures::class
        ];
    }
}
