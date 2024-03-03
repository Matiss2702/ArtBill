<?php

namespace App\Controller\Admin;

use App\Entity\Quotation;
use App\Form\QuotationType;
use App\Repository\QuotationRepository;
use App\Service\CalculAmountService;
use App\Service\GenerateInvoiceService;
use App\Service\SetOwnerAndCompanyService;
use App\Service\SetVersionsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/quotation', name: 'quotation_')]
class QuotationController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(QuotationRepository $quotationRepository): Response
    {
        dd($this->getUser()->getRoles());
        $user = $this->getUser();
        $quotations = $quotationRepository->findLatestQuotationsForCompany($user);

        return $this->render('admin/quotation/index.html.twig', [
            'quotations' => $quotations,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CalculAmountService $calculService, SetOwnerAndCompanyService $setOwnerAndCompany): Response
    {
        $quotation = new Quotation();
        $form = $this->createForm(QuotationType::class, $quotation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $setOwnerAndCompany->process($quotation, $user);
            $calculService->calculAmounts($quotation);
            $quotation->setVersion(0);

            $entityManager->persist($quotation);
            $entityManager->flush();

            return $this->redirectToRoute('admin_quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/quotation/new.html.twig', [
            'quotation' => $quotation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Quotation $quotation, QuotationRepository $quotationRepository): Response
    {
        $previousVersions = $quotationRepository->findAllPreviousVersions($quotation);
        $nextVersions = $quotationRepository->findAllNextVersions($quotation);
        // dd($previousVersions);

        return $this->render('admin/quotation/show.html.twig', [
            'quotation' => $quotation,
            'previousVersions' => $previousVersions,
            'nextVersions' => $nextVersions,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quotation $quotation, EntityManagerInterface $entityManager, CalculAmountService $calculService, SessionInterface $session, SetVersionsService $setVersion): Response
    {
        $bill = new Quotation();
        $setVersion->process($bill, $quotation);
        $form = $this->createForm(QuotationType::class, $bill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calculService->calculAmounts($bill);
            $entityManager->persist($bill);
            $entityManager->flush();
            $previousUrl = $session->get('previous_url');
            return new RedirectResponse($previousUrl);
        }
        $session->set('previous_url', $request->headers->get('referer'));

        return $this->render('admin/quotation/edit.html.twig', [
            'quotation' => $quotation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Quotation $quotation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quotation->getId(), $request->request->get('_token'))) {
            $quotation->setStatus('archived');
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_quotation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/generate-invoice/{id}', name: 'generate_invoice', methods: ['GET'])]
    public function generateInvoice(Quotation $quotation, GenerateInvoiceService $generateInvoiceService, EntityManagerInterface $entityManager): RedirectResponse
    {
        try {
            $invoiceGenerated = $generateInvoiceService->generateInvoice($quotation);
            $entityManager->persist($invoiceGenerated);
            $entityManager->flush();
            $quotation->addInvoice($invoiceGenerated);
            $id = $invoiceGenerated->getId();
            $this->addFlash('success', 'Facture générée');
            return $this->redirectToRoute('admin_invoice_show', ['id' => $id]);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur lors de la génération de la facture');
        }
    }
}
