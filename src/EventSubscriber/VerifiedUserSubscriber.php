<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class VerifiedUserSubscriber implements EventSubscriberInterface
{
    private $security;
    private $urlGenerator;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $user = $this->security->getUser();
        
        // Si l'utilisateur est connecté mais pas encore vérifié
        if ($user && !$user->getIsVerified()) {
            // Obtenez la route actuelle
            $currentRoute = $event->getRequest()->attributes->get('_route');
    
            // Liste des routes autorisées pour les utilisateurs non vérifiés
            $allowedRoutes = ['app_login', 'app_register', 'verify_user', 'default_index', 'user_not_verified'];
    
            // Si la route actuelle n'est pas dans la liste des routes autorisées
            if (!in_array($currentRoute, $allowedRoutes)) {
                // Redirigez l'utilisateur vers la page de profil non vérifié avec un message flash
                $response = new RedirectResponse($this->urlGenerator->generate('user_not_verified'));
                $event->getRequest()->getSession()->getFlashBag()->add('warning', 'Votre compte n\'est pas encore vérifié. Veuillez vérifier votre compte.');
                $event->setResponse($response);
                return;
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}