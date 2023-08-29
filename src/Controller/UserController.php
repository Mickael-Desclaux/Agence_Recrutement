<?php

namespace App\Controller;

use App\Entity\CandidateProfile;
use App\Entity\RecruiterProfile;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/new', name: 'user_new')]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();

            if($user->getRole() == 'ROLE_CANDIDATE') {
                $candidateProfile = new CandidateProfile();
                $candidateProfile->setUser($user);
                $em->persist($candidateProfile);
            } elseif ($user->getRole() == 'ROLE_RECRUITER') {
                $recruiterProfile = new RecruiterProfile();
                $recruiterProfile->setUser($user);
                $em->persist($recruiterProfile);
            }

            $em->flush();

            return $this->redirectToRoute('user_new');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
