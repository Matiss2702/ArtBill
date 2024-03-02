<?php

namespace App\Controller\SuperAdmin;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use App\Service\CalculAmountService;
use App\Service\SetOwnerAndCompanyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[IsGranted('ROLE_SUPERADMIN')]
#[Route('/invoice', 'invoice_')]
class InvoiceController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('superadmin/invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CalculAmountService $calculService, SetOwnerAndCompanyService $setOwnerAndCompany): Response
    {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $setOwnerAndCompany->process($invoice, $user);
            $calculService->calculAmounts($invoice);
            $entityManager->persist($invoice);
            $entityManager->flush();
            $id = $invoice->getId();

            return $this->redirectToRoute('superadmin_invoice_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('superadmin/invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager, CalculAmountService $calculService, SessionInterface $session): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calculService->calculAmounts($invoice);
            $entityManager->persist($invoice);
            $entityManager->flush();
            $previousUrl = $session->get('previous_url');
            return new RedirectResponse($previousUrl);
        }

        return $this->render('superadmin/invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('superadmin_invoice_index', [], Response::HTTP_SEE_OTHER);
    }
}
