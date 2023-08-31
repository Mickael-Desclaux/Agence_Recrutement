<?php

namespace App\Controller\Admin;

use App\Entity\Application;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class ApplicationCrudController extends AbstractCrudController
{
    private $mailer;
    private $entityManager;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager) {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Application::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('jobOfferID')->setLabel('ID annonce');
        yield IntegerField::new('candidateID')->setLabel('ID du candidat');
        yield BooleanField::new('applicationValidation')->setLabel('Validation');
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);

        if ($entityInstance->getApplicationValidation()) {

            $recruiterEmail = $entityInstance->getJobOffer()->getUser()->getEmail();
            $candidateProfile = $entityInstance->getCandidateProfile();

            $email = (new TemplatedEmail())
                ->from('mickaeldesclaux@gmail.com')
                ->to($recruiterEmail)
                ->subject('Nouvelle candidature validée')
                ->htmlTemplate('emails/application_validated.html.twig')
                ->context([
                    'application' => $entityInstance,
                    'candidateProfile' => $candidateProfile,
                ])
                ->attachFromPath(
                    sprintf('%s/public/uploads/resumes/%s', $this->getParameter('kernel.project_dir'), $candidateProfile->getResume())
//                ->attachFromPath(
//                    '/path/to/pdf/folder/' . $candidateProfile->getResume()
                );

            $this->mailer->send($email);

            $this->addFlash('success', 'La candidature a été validée et un email a été envoyé.');
        }
    }
}
