<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\CandidateProfile;
use App\Entity\JobOffer;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class JobOfferController extends AbstractController
{
    #[Route('/recruiter/job/offer/new', name: 'job_offer_new')]
    public function newJobOffer(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        $jobOffer = new JobOffer();
        $jobOffer->setUser($user);
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($jobOffer);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('job_offer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recruiter/job/my_offers', name: 'my_job_offers')]
    public function myJobOffers(UserInterface $user, JobOfferRepository $jobOfferRepository): Response
    {
        $myJobOffers = $jobOfferRepository->findBy(['user' => $user, 'publishValidation' => true]);

        return $this->render('job_offer/my_job_offers.html.twig', [
            'my_job_offers' => $myJobOffers
        ]);
    }

    #[Route('/recruiter/job/my_offers/{id}', name: 'job_offer_detail')]
    public function jobOfferDetail(JobOffer $jobOffer): Response
    {
        $validApplications = [];
        foreach ($jobOffer->getApplications() as $application) {
            if ($application->getApplicationValidation() === true) {
                $validApplications[] = $application;
            }
        }

        return $this->render('job_offer/job_offer_detail.html.twig', [
            'job_offer' => $jobOffer,
            'valid_applications' => $validApplications,
        ]);
    }

    #[Route('/job_offer/{id}', name: 'job_offer_consultation')]
    public function jobOfferConsultation(JobOffer $jobOffer): Response
    {
        return $this->render('job_offer/job_offer_consultation.html.twig', [
            'job_offer' => $jobOffer,
        ]);
    }

    #[Route('/candidate/apply/{jobOfferId}', name: 'job_offer_application')]
    public function apply(int $jobOfferId, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user || $user->getRole() !== 'ROLE_CANDIDATE_VALID') {
            throw new AccessDeniedException('Seuls les candidats peuvent postuler.');
        }

        $jobOffer = $entityManager->getRepository(JobOffer::class)->find($jobOfferId);

        if (!$jobOffer) {
            throw $this->createNotFoundException('Offre d\'emploi non trouvée');
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
