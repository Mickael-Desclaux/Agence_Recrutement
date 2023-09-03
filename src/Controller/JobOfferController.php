<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Entity\User;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class JobOfferController extends AbstractController
{
    #[Route('/job/offer/new', name: 'job_offer_new')]
    public function newJobOffer(Request $request, ManagerRegistry $doctrine, UserInterface $user): Response
    {
        if (!$user instanceof User || !in_array('ROLE_RECRUITER', $user->getRoles())) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

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

    #[Route('/job/my_offers', name: 'my_job_offers')]
    public function myJobOffers(UserInterface $user, JobOfferRepository $jobOfferRepository)
    {
        if (!$user instanceof User || !in_array('ROLE_RECRUITER', $user->getRoles())) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $myJobOffers = $jobOfferRepository->findBy(['user' => $user, 'publishValidation' => true]);

        return $this->render('job_offer/my_job_offers.html.twig', [
            'my_job_offers' => $myJobOffers
        ]);
    }

    #[Route('/job/my_offers/{id}', name: 'job_offer_detail')]
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
}
