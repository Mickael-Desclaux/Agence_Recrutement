<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FileController extends AbstractController
{

    #[Route('/secure-download/{filename}', name: 'secure_download')]
    public function downloadAction(Request $request, $filename)
    {
        try {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, 'Accès refusé. Vous devez être connecté.');
        } catch (AccessDeniedException $e) {
            return $this->redirectToRoute('login');
        }

        $resumesDirectory = $this->getParameter('resumes_directory');
        $file = $resumesDirectory . $filename;

        // Vérifier si le fichier existe et que l'utilisateur a les droits nécessaires ici

        return new BinaryFileResponse($file);
    }
}
