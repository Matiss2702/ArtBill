<?php

namespace App\Service;

class SetVersionsService
{
    public function process($bill, $oldBill): void
    {
        $bill->setDescription($oldBill->getDescription());
        $bill->setAmountHt($oldBill->getAmountHt());
        $bill->setAmountTtc($oldBill->getAmountTtc());
        $bill->setStatus($oldBill->getStatus());
        $bill->setDate($oldBill->getDate());
        $bill->setDueDate($oldBill->getDueDate());
        $bill->setOwner($oldBill->getOwner());
        $bill->setCompany($oldBill->getCompany());
        $bill->setCreatedAt($oldBill->getCreatedAt());
        $bill->setUpdatedAt($oldBill->getUpdatedAt());
        $bill->setVersion($oldBill->getVersion() + 1);

        foreach ($oldBill->getServices() as $service) {
            $bill->addService($service);
        }

        $bill->setPreviousVersion($oldBill);
    }
}
