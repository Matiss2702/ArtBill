<?php

namespace App\Service;

class CalculAmountService
{
    public function calculAmounts($bill): void
    {

        $amountHtTotal = 0;
        $baseVatRate10 = 0;
        $amountVat10 = 0;
        $baseVatRate20 = 0;
        $amountVat20 = 0;
        $services = $bill->getServices();

        foreach ($services as $service) {

            if ($service->getVatRate() === 10) {
                $amountVat10 += $service->getPrice() * $service->getQuantity() * 0.1;
                $baseVatRate10 += $service->getPrice() * $service->getQuantity();
            } elseif ($service->getVatRate() === 20) {
                $amountVat20 += $service->getPrice() * $service->getQuantity() * 0.2;
                $baseVatRate20 += $service->getPrice() * $service->getQuantity();
            }
            $amountHtTotal += $service->getPrice() * $service->getQuantity();
        }
        $baseVatRate0 = $amountHtTotal - ($baseVatRate10 + $baseVatRate20);
        $bill->setBaseVatRate0($baseVatRate0);
        $bill->setVatRate10($amountVat10);
        $bill->setBaseVatRate10($baseVatRate10);
        $bill->setVatRate20($amountVat20);
        $bill->setBaseVatRate20($baseVatRate20);
        $bill->setAmountHt($amountHtTotal);

        $amountTtc = ($amountVat10 + $baseVatRate10) + ($amountVat20 + $baseVatRate20) + $baseVatRate0;
        $bill->setAmountTtc($amountTtc);
    }
}
