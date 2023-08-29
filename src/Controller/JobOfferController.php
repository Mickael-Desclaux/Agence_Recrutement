<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Entity\User;
use App\Form\JobOfferType;
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
}
