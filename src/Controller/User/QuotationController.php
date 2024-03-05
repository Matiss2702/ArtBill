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

        return $this->render('user/quotation/show.html.twig', [
            'quotation' => $quotation,
            'previousVersions' => $previousVersions,
            'nextVersions' => $nextVersions,
        ]);
    }
}
