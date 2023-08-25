<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecruiterProfileController extends AbstractController
{
    #[Route('/recruiter/profile', name: 'app_recruiter_profile')]
    public function index(): Response
    {
        return $this->render('recruiter_profile/index.html.twig', [
            'controller_name' => 'RecruiterProfileController',
        ]);
    }
}
