<?php

namespace App\Controller\Admin;

use App\Repository\InvoiceRepository;
use App\Repository\QuotationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/archived', name: 'archived_')]
class ArchivedController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(QuotationRepository $quotationRepository, InvoiceRepository $invoiceRepository): Response
    {
        $user = $this->getUser();
        return $this->render('admin/archive.html.twig', [
            'quotations' => $quotationRepository->findAllArchivedByCompany($user),
            'invoices' => $invoiceRepository->findAllArchivedByCompany($user),
        ]);
    }
}
