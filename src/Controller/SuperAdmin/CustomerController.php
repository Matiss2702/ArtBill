<?php

namespace App\Controller\SuperAdmin;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Form\CustomerType;
use App\Repository\QuotationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer', name: 'customer_')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'get')]
    public function index(CustomerRepository $customerRepository): Response
    {
        return $this->render('front/customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '[0-9a-fA-F\-]+'], methods: 'get')]
    public function show(Customer $customer, QuotationRepository $quotationRepository): Response
    {
        return $this->render('front/customer/show.html.twig', [
            'customer' => $customer,
            'quotations' =>  $quotationRepository->findLatestQuotationsForCustomer($customer->getId()),
        ]);
    }
}
