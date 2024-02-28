<?php

namespace App\Controller;

use App\Entity\Reply;
use App\Entity\User;
use App\Entity\Company;
use App\Form\RegistrationFormType;
use App\Form\UserRegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMailService $mail, JWTService $jwt): Response
    {
        $user = new User();
        $company = new Company();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set company details
            $company->setName($form->get('companyName')->getData());
            $company->setVatNumber($form->get('vatNumber')->getData());
            $company->setZipCode($form->get('zipCode')->getData());

            // Persist company entity
            $entityManager->persist($company);
            $entityManager->flush(); // Flush here to ensure that the company ID is generated.

            // Set user details
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $user->setRoles(['ROLE_ADMIN']);
            $user->setCompany($company);

            // Persist user entity
            $entityManager->persist($user);
            $entityManager->flush();

            // Gestion JWT et envoi de mail ici
            $header = ['typ' => 'JWT', 'alg' => 'HS256'];
            $payload = ['user_id' => $user->getId()];
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
            $mail->send('team.artbill@outlook.fr', $user->getEmail(), 'Activation de votre compte chez ArtBill', 'register', compact('user', 'token'));

            // Authentification et redirection de l'utilisateur
            return $userAuthenticator->authenticateUser($user, $authenticator, $request);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UserRepository $usersRepository, EntityManagerInterface $em): Response
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $usersRepository->find($payload['user_id']);

            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('profile_index');
            }
        }
        // Ici un problème se pose dans le token
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoiverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UserRepository $usersRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Cet utilisateur est déjà activé');
            return $this->redirectToRoute('admin/profile_index');
        }

        // On génère le JWT de l'utilisateur
        // On crée le Header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        // On crée le Payload
        $payload = [
            'user_id' => $user->getId()
        ];

        // On génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
        dd($token);
        // On envoie un mail
        $mail->send(
            'team.artbill@outlook.fr',
            $user->getEmail(),
            'Activation de votre compte sur ArtBill',
            'register',
            compact('user', 'token')
        );
        $this->addFlash('success', 'Email de vérification envoyé');
        return $this->redirectToRoute('/');
    }

    #[Route('/admin/register', name: 'company_user_register')]
    public function registerCompanyUser(Request $request, UserPasswordHasherInterface $passwordHasher, SendMailService $mail,EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $companyUser = $this->getUser();
        $company = $companyUser->getCompany();
        $user = new User();
        $user->setCompany($company);
        $user->setIsVerified(true);
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $user->setRoles(['ROLE_USER']);
            $user->setCompany($this->getUser()->getCompany());

            $entityManager->persist($user);
            $entityManager->flush();

            $mail->send('team.artbill@outlook.fr', $user->getEmail(), 'Activation de votre compte chez ArtBill', 'register-user', compact('user'));

            // return $this->redirectToRoute('/');
        }

        return $this->render('registration/company_user_register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
