<?php

namespace App\Exception;

use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        dd('dvsev');
        // Gérez ici la réponse personnalisée pour l'erreur 500
        $content = "Erreur 500: Accès refusé par l'annotation de contrôleur @IsGranted(\"ROLE_SUPERADMIN\")";

        return new Response($content, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
