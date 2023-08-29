<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CandidateProfileType;
use App\Repository\CandidateProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CandidateProfileController extends AbstractController
{
    #[Route('/candidate/profile', name: 'candidate_profile')]
    public function profileView(CandidateProfileRepository $candidateProfileRepository, UserInterface $user): Response
    {
        if (!$user instanceof User || !in_array('ROLE_CANDIDATE', $user->getRoles())) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $recruiterProfile = $candidateProfileRepository->findOneBy(['user' => $user]);

        if (!$recruiterProfile) {
            throw $this->createNotFoundException('Aucun profil trouvé pour ce candidat');
        }

        return $this->render('candidate_profile/index.html.twig', [
            'candidateProfile' => $recruiterProfile,
        ]);
    }

    #[Route('/candidate/profile/edit', name: 'candidate_profile_edit')]
    public function editProfile(Request $request, UserInterface $user, CandidateProfileRepository $candidateProfileRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if (!$user instanceof User || !in_array('ROLE_CANDIDATE', $user->getRoles())) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $candidateProfile = $candidateProfileRepository->findOneBy(['user' => $user]);

        if (!$candidateProfile) {
            throw $this->createNotFoundException('Aucun profil trouvé pour ce candidat');
        }

        $form = $this->createForm(CandidateProfileType::class, $candidateProfile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $resumeFile */
            $resumeFile = $form->get('resume')->getData();

            if ($resumeFile) {
                $originalFilename = pathinfo($resumeFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $resumeFile->guessExtension();

                try {
                    $resumeFile->move(
                        $this->getParameter('resumes_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'resume' property to store the PDF file name
                // instead of its contents
                $candidateProfile->setResume($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('candidate_profile');
        }

        return $this->render('candidate_profile/edit.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }
}
