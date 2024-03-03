<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Assurez-vous que $user n'est pas null et qu'il a une compagnie associée
        if (!$user || !$user->getCompany()) {
            throw $this->createNotFoundException('Profil ou compagnie de l\'utilisateur non trouvés.');
        }

        // Renvoyer les données de l'utilisateur à la vue
        return $this->render('admin/profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
