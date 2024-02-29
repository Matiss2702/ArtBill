<?php

namespace App\Service;

use App\Entity\Invoice;

class GenerateInvoiceService
{
    public function generateInvoice($quotation): Invoice
    {

        $invoice = new Invoice();
        $invoice->setDescription($quotation->getDescription());
        $invoice->setOwner($quotation->getOwner());
        $invoice->setCustomer($quotation->getCustomer());
        $invoice->setCompany($quotation->getCompany());
        $invoice->setDate($quotation->getDate());
        $invoice->setDueDate($quotation->getDueDate());
        $invoice->setQuotations($quotation);


        foreach ($quotation->getServices() as $service) {
            $invoice->addService($service);
        }
        if ($quotation->getStatus() == 'paid') {
            $invoice->setIsPaid(true);
        } else {
            $invoice->setIsPaid(false);
        }

        $invoice->setDate($quotation->getDate());

        $invoice->setVatRate10($quotation->getVatRate10());
        $invoice->setBaseVatRate10($quotation->getBaseVatRate10());
        $invoice->setVatRate20($quotation->getVatRate20());
        $invoice->setBaseVatRate20($quotation->getBaseVatRate20());
        $invoice->setAmountHt($quotation->getAmountHt());
        $invoice->setAmountTtc($quotation->getAmountTtc());
        $invoice->setBaseVatRate0($quotation->getBaseVatRate0());
        $invoice->setCreatedAt(new \DateTime());
        $invoice->setUpdatedAt(new \DateTime());
        $invoice->setQuotations($quotation);

        return $invoice;
    }
}
