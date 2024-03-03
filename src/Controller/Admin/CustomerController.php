<?php

namespace App\Controller\Admin;

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

#[IsGranted('ROLE_ADMIN')]
#[Route('/customer', name: 'customer_')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'get')]
    public function index(CustomerRepository $customerRepository): Response
    {
        return $this->render('admin/customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, CustomerRepository $customerRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $existingCustomer = $customerRepository->findOneByEmail($customer->getEmail());
    
            if ($existingCustomer) {
                $this->addFlash('danger', "Cette adresse mail est déjà utilisée");
                return $this->redirectToRoute('admin_customer_new');
            }

            $manager->persist($customer);
            $manager->flush();

            $this->addFlash('success', "Le client {$customer->getId()} a bien été enregistré");

            return $this->redirectToRoute('admin_customer_show', [
                'id' => $customer->getId()
            ]);
        }

        return $this->render('admin/customer/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Customer $customer, QuotationRepository $quotationRepository): Response
    {
        return $this->render('admin/customer/show.html.twig', [
            'customer' => $customer,
            'quotations' =>  $quotationRepository->findLatestQuotationsForCustomer($customer),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function update(Request $request, CustomerRepository $customerRepository, EntityManagerInterface $entityManager, string $id,): Response
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
                $entityManager->flush();
                
                $this->addFlash('success', "Le client {$customer->getName()} a bien été modifié.");
                
                return $this->redirectToRoute('admin_customer_show', [
                    'id' => $customer->getId()
                ]);
            }
        }        

        return $this->render('admin/customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
