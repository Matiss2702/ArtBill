<?php

namespace App\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_SUPERADMIN')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile_index')]
    public function index(): Response
    {
        return $this->render('superadmin/profile/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }

    #[Route('/commandes', name: 'orders')]
    public function orders(): Response
    {
        return $this->render('superadmin/profile/index.html.twig', [
            'controller_name' => 'Commandes de l\'utilisateur',
        ]);
    }
}
