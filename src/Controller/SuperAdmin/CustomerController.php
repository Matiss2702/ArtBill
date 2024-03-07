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

        try {
            $form->handleRequest($request);
        } catch (\Throwable $th) {
            if (strpos($th->getMessage(), "zip_code") !== false) {
                $this->addFlash('danger', "Le code postal doit être composé uniquement de chiffres");
            }
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $existingCustomer = $customerRepository->findOneByEmail($customer->getEmail());
    
            if ($existingCustomer) {
                $this->addFlash('danger', "Cette adresse mail est déjà utilisée");
                return $this->redirectToRoute('superadmin_customer_new');
            }
            $customer->setCompany($this->getUser()->getCompany());

            $manager->persist($customer);
            $manager->flush();

            $this->addFlash('success', "Le client {$customer} a bien été enregistré");

            return $this->redirectToRoute('superadmin_customer_show', [
                'id' => $customer->getId()
            ]);
        }

        return $this->render('superadmin/customer/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function update(Request $request, CustomerRepository $customerRepository, EntityManagerInterface $entityManager, string $id,): Response
    {
        $customer = $customerRepository->getOneById($id);
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "Le client {$customer->getName()} a bien été modifié.");
            
            return $this->redirectToRoute('superadmin_customer_show', [
                'id' => $customer->getId()
            ]);
        }        

        return $this->render('superadmin/customer/edit.html.twig', [
            'form' => $form,
            'customer' => $customer
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['GET','POST'])]
    public function delete(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('superadmin_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
