<?php

namespace App\Controller;

use App\Repository\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(JobOfferRepository $jobOfferRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $jobOffers = $jobOfferRepository->findBy(['publishValidation' => true]);

        return $this->render('home/index.html.twig', [
            'job_offers' => $jobOffers,
        ]);
    }
}
