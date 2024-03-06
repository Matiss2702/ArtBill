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
        $user = $this->getUser();
        if (in_array('ROLE_SUPERADMIN', $user->getRoles(), true)) {
            $customers = $customerRepository->findAll($user); 
        } else {
            $customers = $customerRepository->findCustomersForCompany($user); 
        }

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
