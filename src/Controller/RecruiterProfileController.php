<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RecruiterProfileType;
use App\Repository\RecruiterProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class RecruiterProfileController extends AbstractController
{
    #[Route('/recruiter/profile', name: 'recruiter_profile')]
    public function profileView(RecruiterProfileRepository $recruiterProfileRepository, UserInterface $user): Response
    {
        if (!$user instanceof User || !in_array('ROLE_RECRUITER', $user->getRoles())) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $recruiterProfile = $recruiterProfileRepository->findOneBy(['user' => $user]);

        if (!$recruiterProfile) {
            throw $this->createNotFoundException('Aucun profil trouvé pour ce recruteur');
        }

        return $this->render('recruiter_profile/index.html.twig', [
            'recruiterProfile' => $recruiterProfile,
        ]);
    }

    #[Route('/recruiter/profile/edit', name: 'recruiter_profile_edit')]
    public function editProfile(Request $request, UserInterface $user, RecruiterProfileRepository $recruiterProfileRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$user instanceof User || !in_array('ROLE_RECRUITER', $user->getRoles())) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $recruiterProfile = $recruiterProfileRepository->findOneBy(['user' => $user]);

        if (!$recruiterProfile) {
            throw $this->createNotFoundException('Aucun profil trouvé pour ce recruteur');
        }

        $form = $this->createForm(RecruiterProfileType::class, $recruiterProfile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('recruiter_profile');
        }

        return $this->render('recruiter_profile/edit.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }
}
