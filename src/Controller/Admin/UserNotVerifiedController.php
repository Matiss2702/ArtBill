<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/not', name: 'not_')]
class UserNotVerifiedController extends AbstractController
{
    #[Route('/verified', name: 'verified')]
    public function index(): Response
    {
        return $this->render('admin/user_not_verified/index.html.twig', [
            'controller_name' => 'UserNotVerifiedController',
        ]);
    }
}
