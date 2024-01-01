<?php

namespace App\Controller\Front;

use App\Entity\Quotation;
use App\Entity\Service;
use App\Form\QuotationType;
use App\Repository\QuotationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quotation', name: 'quotation_')]
class QuotationController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(QuotationRepository $quotationRepository): Response
    {
        return $this->render('quotation/index.html.twig', [
            'quotations' => $quotationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quotation = new Quotation();
        $form = $this->createForm(QuotationType::class, $quotation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quotation);
            $entityManager->flush();

            return $this->redirectToRoute('quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quotation/new.html.twig', [
            'quotation' => $quotation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Quotation $quotation): Response
    {
        return $this->render('quotation/show.html.twig', [
            'quotation' => $quotation,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quotation $quotation, EntityManagerInterface $entityManager): Response
    {

        // dummy code - add some example services to the quotation
        $servicePlaceholder = new Service();
        $quotation->getServices()->add($servicePlaceholder);
        // (otherwise, the template will render an empty list of tags)


        $form = $this->createForm(QuotationType::class, $quotation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('quotation_index', [], Response::HTTP_SEE_OTHER);
        }

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
