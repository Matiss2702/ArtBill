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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_SUPERADMIN')]
#[Route('/customer', name: 'customer_')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'get')]
    public function index(CustomerRepository $customerRepository): Response
    {
        return $this->render('superadmin/customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '[0-9a-fA-F\-]+'], methods:  ['GET'])]
    public function show(Customer $customer, QuotationRepository $quotationRepository): Response
    {
        return $this->render('superadmin/customer/show.html.twig', [
            'customer' => $customer,
            'quotations' =>  $quotationRepository->findLatestQuotationsForCustomer($customer),
        ]);
    }


    #[Route('/new', name: 'new', methods: ['get', 'post'])]
    public function new(Request $request, EntityManagerInterface $manager, CustomerRepository $customerRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $existingCustomer = $customerRepository->findOneByEmail($customer->getEmail());
    
            if ($existingCustomer) {
                $this->addFlash('danger', "Cette adresse mail est déjà utilisée");
                return $this->redirectToRoute('superadmin_customer_new');
            }
            $customer->setCompany($this->getUser()->getCompany());

            $manager->persist($customer);
            $manager->flush();

            $this->addFlash('success', "Le client {$customer->getId()} a bien été enregistré");

            return $this->redirectToRoute('superadmin_customer_show', [
                'id' => $customer->getId()
            ]);
        }

        return $this->render('superadmin/customer/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '[0-9a-fA-F\-]+'], methods: ['get', 'post'])]
    public function update(String  $id, CustomerRepository $customerRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $customer = $customerRepository->getOneById($id);
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submittedEmail = $form->get('email')->getData();
            $existingCustomer = $customerRepository->findOneBy(['email' => $submittedEmail]);

            if ($existingCustomer && $existingCustomer->getId() !== $customer->getId()) {
                $this->addFlash('error', 'Cet email n\'est pas disponible.');
            } else {
                $manager->flush();
                
                $this->addFlash('success', "Le client {$customer->getName()} a bien été modifié.");
                
                return $this->redirectToRoute('superadmin_customer_show', [
                    'id' => $customer->getId()
                ]);
            }
        }        

        return $this->render('superadmin/customer/update.html.twig', [
            'form' => $form,
            'customer' => $customer
        ]);
    }


    #[Route('/delete/{id}/{token}', name: 'delete', requirements: ['id' => '[0-9a-fA-F\-]+'], methods: 'get')]
    public function delete(Customer $customer, string $token, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $token)) {
            $manager->remove($customer);
            $manager->flush();

            $this->addFlash('success', "Le client {$customer->getId()} a bien été supprimé");
        }

        return $this->redirectToRoute('superadmin_customer_index');
    }
}
