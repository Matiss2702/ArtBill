<?php

namespace App\Controller\SuperAdmin;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_SUPERADMIN')]
#[Route('/service', 'service_')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('superadmin/service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $this->getUser()->getCompany();
            $service->setCompany($company);

            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('superadmin_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('superadmin/service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('superadmin/service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('superadmin_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('superadmin/service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('superadmin_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
