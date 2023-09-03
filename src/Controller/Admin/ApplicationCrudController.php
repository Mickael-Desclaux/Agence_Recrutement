<?php

namespace App\Controller\Admin;

use App\Entity\Application;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Mailer\MailerInterface;

class ApplicationCrudController extends AbstractCrudController
{
    private MailerInterface $mailer;
    private EntityManagerInterface $entityManager;
    private EmailService $emailService;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager, EmailService $emailService) {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
    }

    public static function getEntityFqcn(): string
    {
        return Application::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('jobOfferID')->setLabel('ID annonce');
        yield IntegerField::new('candidateProfileId')->setLabel('ID du candidat');
        yield TextField::new('candidateResumeLink', 'CV du candidat')->renderAsHtml();
        yield BooleanField::new('applicationValidation')->setLabel('Validation');
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);

        if ($entityInstance->getApplicationValidation()) {

            $this->emailService->sendApplicationValidationEmail($entityInstance, $this->getParameter('kernel.project_dir'));

            $this->addFlash('success', 'La candidature a été validée et un email a été envoyé.');
        }
    }
}
