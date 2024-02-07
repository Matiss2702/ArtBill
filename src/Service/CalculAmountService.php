<?php

namespace App\Service;

class CalculAmountService
{
    public function calculAmounts($bill): void
    {

        $amount = 0;
        $services = $bill->getServices();

        foreach ($services as $service) {
            $amount += $service->getPrice() * $service->getQuantity();
        }
        $bill->setAmountHt($amount);

        $amountTtc = $amount * 1.2;
        $bill->setAmountTtc($amountTtc);
    }
}
