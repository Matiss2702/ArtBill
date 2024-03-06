<?php

namespace App\Controller\User;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/service', name: 'service_')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $user = $this->getUser();
        return $this->render('user/service/index.html.twig', [
            'services' => $serviceRepository->findAllByCompany($user),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('user/service/show.html.twig', [
            'service' => $service,
        ]);
    }
}
