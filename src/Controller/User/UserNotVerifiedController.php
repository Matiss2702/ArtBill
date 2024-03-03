<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/not', name: 'not_')]
class UserNotVerifiedController extends AbstractController
{
    #[Route('/verified', name: 'verified')]
    public function index(): Response
    {
        return $this->render('user/user_not_verified/index.html.twig', [
            'controller_name' => 'UserNotVerifiedController',
        ]);
    }
}
