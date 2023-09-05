<?php

namespace App\Controller;

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
        $candidateProfile = $candidateProfileRepository->findOneBy(['user' => $user]);

        return $this->render('candidate_profile/index.html.twig', [
            'candidateProfile' => $candidateProfile,
        ]);
    }

    #[Route('/candidate/profile/edit', name: 'candidate_profile_edit')]
    public function editProfile(Request $request, UserInterface $user, CandidateProfileRepository $candidateProfileRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $candidateProfile = $candidateProfileRepository->findOneBy(['user' => $user]);

        $form = $this->createForm(CandidateProfileType::class, $candidateProfile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $resumeFile */
            $resumeFile = $form->get('resume')->getData();

            if ($resumeFile) {
                $originalFilename = pathinfo($resumeFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $resumeFile->guessExtension();

                try {
                    $resumeFile->move(
                        $this->getParameter('resumes_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo "Une erreur s'est produite lors du téléchargement du fichier. Veuillez réessayer.";
                    error_log($e->getMessage());
                }

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
