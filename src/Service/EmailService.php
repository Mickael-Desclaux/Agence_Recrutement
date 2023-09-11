<?php

namespace App\Service;

use App\Entity\Application;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendApplicationValidationEmail(Application $application, string $projectDir): void
    {
        $recruiterEmail = $application->getJobOffer()->getUser()->getEmail();
        $candidateProfile = $application->getCandidateProfile();

        $email = (new TemplatedEmail())
            ->from('mickaeldesclaux@gmail.com')
            ->to($recruiterEmail)
            ->subject('Nouvelle candidature validée')
            ->htmlTemplate('emails/application_validated.html.twig')
            ->context([
                'application' => $application,
                'candidateProfile' => $candidateProfile,
            ])
            ->attachFromPath(
                sprintf('%s/public/uploads/resumes/%s', $projectDir, $candidateProfile->getResume())
            );

        $this->mailer->send($email);
    }
}
