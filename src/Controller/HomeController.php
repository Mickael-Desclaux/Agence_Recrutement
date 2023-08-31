<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\CandidateProfile;
use App\Entity\JobOffer;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(JobOfferRepository $jobOfferRepository): Response
    {
        $jobOffers = $jobOfferRepository->findBy(['publishValidation' => true]);

        return $this->render('home/index.html.twig', [
            'job_offers' => $jobOffers,
        ]);
    }

    #[Route('/apply/{jobOfferId}', name: 'job_offer_application')]
    public function apply(int $jobOfferId, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user || $user->getRole() !== 'ROLE_CANDIDATE') {
            throw new AccessDeniedException('Seuls les candidats peuvent postuler.');
        }

        $jobOffer = $entityManager->getRepository(JobOffer::class)->find($jobOfferId);

        if (!$jobOffer) {
            throw $this->createNotFoundException('Job Offer not found');
        }

        $candidateProfileRepository = $entityManager->getRepository(CandidateProfile::class);
        $candidateProfile = $candidateProfileRepository->findOneBy(['user' => $user]);

        $application = new Application();
        $application->setCandidate($user);
        $application->setJobOffer($jobOffer);
        $application->setCandidateProfile($candidateProfile);

        $entityManager->persist($application);
        $entityManager->flush();

        $this->addFlash('success', 'Votre candidature a été soumise.');

        return $this->redirectToRoute('home');
    }
}
