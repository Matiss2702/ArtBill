<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'admin_profile_index')]
    public function index(): Response
    {
        return $this->render('admin/profile/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }

    #[Route('/commandes', name: 'orders')]
    public function orders(): Response
    {
        return $this->render('admin/profile/index.html.twig', [
            'controller_name' => 'Commandes de l\'utilisateur',
        ]);
    }
}
