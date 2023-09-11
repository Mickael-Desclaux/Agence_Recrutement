<?php

namespace App\Controller;

use App\Entity\CandidateProfile;
use App\Entity\RecruiterProfile;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/new', name: 'user_new')]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleNewUserFormSubmission($entityManager, $userPasswordHasher, $user);
            return $this->redirectToRoute('user_new');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function handleNewUserFormSubmission(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, User $user): void
    {
        $this->hashUserPassword($userPasswordHasher, $user);
        $this->persistUserAndFlush($entityManager, $user);
        $this->addFlash('notice', 'Votre demande a bien été enregistrée, veuillez attendre la validation du compte par nos consultants');
    }

    private function hashUserPassword(UserPasswordHasherInterface $userPasswordHasher, User $user): void
    {
        $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($password);
    }

    private function persistUserAndFlush(EntityManagerInterface $entityManager, User $user): void
    {
        $entityManager->persist($user);

        if($user->getRole() === 'ROLE_CANDIDATE') {
            $profile = new CandidateProfile();
        } elseif ($user->getRole() === 'ROLE_RECRUITER') {
            $profile = new RecruiterProfile();
        } else {
            throw new InvalidArgumentException('Role inconnu: ' . $user->getRole());
        }

        $profile->setUser($user);
        $entityManager->persist($profile);
        $entityManager->flush();
    }
}
