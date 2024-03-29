<?php

namespace App\Controller\SuperAdmin;

use App\Repository\InvoiceRepository;
use App\Repository\QuotationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_SUPERADMIN')]
#[Route('/archived', name: 'archived_')]
class ArchivedController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(QuotationRepository $quotationRepository, InvoiceRepository $invoiceRepository): Response
    {
        $user = $this->getUser();
        return $this->render('superadmin/archive.html.twig', [
            'quotations' => $quotationRepository->findAllArchived($user),
            'invoices' => $invoiceRepository->findAllArchived($user),
        ]);
    }
}
