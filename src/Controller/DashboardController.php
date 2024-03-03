<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(CustomerRepository $customerRepository, InvoiceRepository $invoiceRepository): Response
    {
        $customers = $customerRepository->findCustomersForCompany($this->getUser()); 

        $customerData = [];
        foreach ($customers as $customer) {
            $invoices = $invoiceRepository->findBy(['customer' => $customer]);
            $totalAmounts = $this->calculateTotalInvoiceAmount($invoices);

            $quotationsCount = count($customer->getQuotations());

            $customerData[$customer->getName()] = [
                'numberOfInvoices' => count($invoices),
                'numberOfQuotations' => $quotationsCount,
                'totalAmountHT' => $totalAmounts['totalAmountHT'],
                'totalAmountTTC' => $totalAmounts['totalAmountTTC'],
            ];
        }
        // dd($customerData);

        return $this->render('dashboard.html.twig', [
            'customerData' => $customerData,
        ]);
    }

    private function calculateTotalInvoiceAmount($invoices): array
    {
        $totalAmountHT = 0;
        $totalAmountTTC = 0;

        foreach ($invoices as $invoice) {
            $totalAmountHT += $invoice->getAmountHt();
            $totalAmountTTC += $invoice->getAmountTtc();
        }

        return [
            'totalAmountHT' => $totalAmountHT,
            'totalAmountTTC' => $totalAmountTTC
        ];
    }
}
