<?php

namespace App\Controller\Front;

use App\Entity\Quotation;
use App\Entity\Service;
use App\Form\QuotationType;
use App\Repository\QuotationRepository;
use App\Service\CalculAmount;
use App\Service\CalculAmountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quotation', name: 'quotation_')]
class QuotationController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(QuotationRepository $quotationRepository): Response
    {
        return $this->render('quotation/index.html.twig', [
            'quotations' => $quotationRepository->findLatestQuotations(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CalculAmountService $calculService): Response
    {
        $quotation = new Quotation();
        $form = $this->createForm(QuotationType::class, $quotation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $quotation->setOwner($user);
            $user = $quotation->getOwner();
            $calculService->calculAmounts($quotation);


            $entityManager->persist($quotation);
            $entityManager->flush();

            return $this->redirectToRoute('front_quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quotation/new.html.twig', [
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

        return $this->render('quotation/show.html.twig', [
            'quotation' => $quotation,
            'previousVersions' => $previousVersions,
            'nextVersions' => $nextVersions,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quotation $quotation, EntityManagerInterface $entityManager, CalculAmountService $calculService, RequestStack $requestStack, SessionInterface $session): Response
    {
        $newQuotation = new Quotation();
    
        $newQuotation->setDescription($quotation->getDescription());
        $newQuotation->setAmountHt($quotation->getAmountHt());
        $newQuotation->setAmountTtc($quotation->getAmountTtc());
        $newQuotation->setStatus($quotation->getStatus());
        $newQuotation->setDate($quotation->getDate());
        $newQuotation->setDueDate($quotation->getDueDate());
        $newQuotation->setOwner($quotation->getOwner());
        $newQuotation->setCompany($quotation->getCompany());
        $newQuotation->setVersion($quotation->getVersion() + 1);
        
        foreach ($quotation->getServices() as $service) {
            $newQuotation->addService($service);
        }

        $newQuotation->setPreviousVersion($quotation);

        $form = $this->createForm(QuotationType::class, $newQuotation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $calculService->calculAmounts($newQuotation);
    
            $entityManager->persist($newQuotation);
            $entityManager->flush();
            
            $previousUrl = $session->get('previous_url');
    
            return new RedirectResponse($previousUrl);
        }

        $session->set('previous_url', $request->headers->get('referer'));

        return $this->render('quotation/edit.html.twig', [
            'quotation' => $quotation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Quotation $quotation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quotation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quotation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quotation_index', [], Response::HTTP_SEE_OTHER);
    }
}
