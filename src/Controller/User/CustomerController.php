<?php

namespace App\Controller\User;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Form\CustomerType;
use App\Repository\QuotationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/customer', name: 'customer_')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'get')]
    public function index(CustomerRepository $customerRepository): Response
    {
        $user = $this->getUser();
        $customer = $customerRepository->findCustomersForCompany($user);

        return $this->render('user/customer/index.html.twig', [
            'customers' => $customer,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '[0-9a-fA-F\-]+'], methods:  ['GET'])]
    public function show(Customer $customer, QuotationRepository $quotationRepository): Response
    {
        return $this->render('user/customer/show.html.twig', [
            'customer' => $customer,
            'quotations' =>  $quotationRepository->findLatestQuotationsForCustomer($customer),
        ]);
    }
}
