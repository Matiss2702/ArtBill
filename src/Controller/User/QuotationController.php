<?php

namespace App\Controller\User;

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

#[IsGranted('ROLE_USER')]
#[Route('/quotation', name: 'quotation_')]
class QuotationController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(QuotationRepository $quotationRepository): Response
    {
        $user = $this->getUser();
        $quotations = $quotationRepository->findLatestQuotationsForCompany($user);

        return $this->render('user/quotation/index.html.twig', [
            'quotations' => $quotations,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Quotation $quotation, QuotationRepository $quotationRepository): Response
    {
        $previousVersions = $quotationRepository->findAllPreviousVersions($quotation);
        $nextVersions = $quotationRepository->findAllNextVersions($quotation);
        // dd($previousVersions);

        return $this->render('user/quotation/show.html.twig', [
            'quotation' => $quotation,
            'previousVersions' => $previousVersions,
            'nextVersions' => $nextVersions,
        ]);
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
            return $this->redirectToRoute('user_invoice_show', ['id' => $id]);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur lors de la génération de la facture');
        }
    }
}
