<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Customer;
use App\Form\CustomerRegistrationType;
use App\Form\CustomerUpdateType;
use App\Repository\AddressRepository;
use App\Repository\CustomerRepository;
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
        return $this->render('customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d{1,5}'], methods: 'get')]
    public function show(Customer $customer): Response
    {
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }


    #[Route('/new', name: 'new', methods: ['get', 'post'])]
    public function new(Request $request, EntityManagerInterface $manager, CustomerRepository $customerRepository, AddressRepository $addressRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerRegistrationType::class, $customer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $customer->getAddress();

            $existingCustomer = $customerRepository->findOneByEmail($customer->getEmail());
    
            if ($existingCustomer) {
                $this->addFlash('danger', "Cette adresse mail est déjà utilisée");
                return $this->redirectToRoute('customer_new');
            }

            $existingAddress = $addressRepository->findOneByExactAddress($address->getCity(), $address->getStreet(), $address->getZipCode(), $address->getCountry(),);
    
            if ($existingAddress) {
                $existingAddress->addCustomer($customer);
            } else {
                $manager->persist($address);
            }

            $manager->persist($customer);
            $manager->flush();

            $this->addFlash('success', "Le client {$customer->getId()} a bien été enregistré");

            return $this->redirectToRoute('customer_show', [
                'id' => $customer->getId()
            ]);
        }

        return $this->render('customer/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d{1,5}'], methods: ['get', 'post'])]
    public function update(int $id, CustomerRepository $customerRepository, Request $request, EntityManagerInterface $manager, AddressRepository $addressRepository): Response
    {
        $customer = $customerRepository->find($id);
        $form = $this->createForm(CustomerUpdateType::class, $customer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $customer->getAddress();

            $existingAddress = $addressRepository->findOneByExactAddress($address->getCity(), $address->getStreet(), $address->getZipCode(), $address->getCountry(),);
    
            if ($existingAddress) {
                $existingAddress->addCustomer($customer);
            } else {
                $newAddress = new Address();
                $newAddress->setCity($address->getCity());
                $newAddress->setStreet($address->getStreet());
                $newAddress->setZipCode($address->getZipCode());
                $newAddress->setCountry($address->getCountry());
                $newAddress->addCustomer($customer);

                $manager->persist($newAddress);

                $customer->setAddress($newAddress);
            }
            
            $manager->flush();

            $this->addFlash('success', "Le client {$customer->getName()} a bien été modifié");

            return $this->redirectToRoute('customer_show', [
                'id' => $customer->getId()
            ]);
        }

        return $this->render('customer/update.html.twig', [
            'form' => $form,
            'customer' => $customer
        ]);
    }

    // #[Route('/delete/{id}/{token}', name: 'delete', requirements: ['id' => '\d{1,5}'], methods: 'get')]
    // public function delete(Customer $customer, string $token, EntityManagerInterface $manager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $customer->getId(), $token)) {
    //         $manager->remove($customer);
    //         $manager->flush();

    //         $this->addFlash('success', "Le client {$customer->getId()} a bien été supprimé");
    //     }

    //     return $this->redirectToRoute('post_index');
    // }
}
