<?php

namespace App\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_SUPERADMIN')]
#[Route('/not', name: 'not_')]
class UserNotVerifiedController extends AbstractController
{
    #[Route('/verified', name: 'verified')]
    public function index(): Response
    {
        return $this->render('superadmin/user_not_verified/index.html.twig', [
            'controller_name' => 'UserNotVerifiedController',
        ]);
    }
}
