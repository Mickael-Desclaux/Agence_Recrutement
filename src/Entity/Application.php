<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $jobOfferID = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "applications")]
    private ?User $candidate;

    #[ORM\ManyToOne(targetEntity: JobOffer::class, inversedBy: "applications")]
    private ?JobOffer $jobOffer;

    #[ORM\ManyToOne(targetEntity: CandidateProfile::class, inversedBy: "applications")]
    private ?CandidateProfile $candidateProfile;

    #[ORM\Column]
    private bool $applicationValidation = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobOfferID(): ?int
    {
        return $this->jobOfferID;
    }

    public function setJobOfferID(int $jobOfferID): static
    {
        $this->jobOfferID = $jobOfferID;

        return $this;
    }

    public function isApplicationValidation(): ?bool
    {
        return $this->applicationValidation;
    }

    public function getApplicationValidation(): ?bool
    {
        return $this->applicationValidation;
    }

    public function setApplicationValidation(bool $applicationValidation): static
    {
        $this->applicationValidation = $applicationValidation;

        return $this;
    }

    public function getJobOffer(): ?JobOffer
    {
        return $this->jobOffer;
    }

    public function setJobOffer(?JobOffer $jobOffer): void
    {
        $this->jobOffer = $jobOffer;
    }

    public function setCandidate(User $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getCandidate(): ?User
    {
        return $this->candidate;
    }

    public function getCandidateProfile(): ?CandidateProfile
    {
        return $this->candidateProfile;
    }

    public function setCandidateProfile(?CandidateProfile $candidateProfile): void
    {
        $this->candidateProfile = $candidateProfile;
    }

    public function getCandidateProfileId(): ?int
    {
        return $this->candidateProfile?->getId();
    }

    public function getCandidateResumeLink(): ?string
    {
        return $this->candidateProfile ? sprintf('<a href="/uploads/resumes/%s" target="_blank">Voir le CV</a>', $this->candidateProfile->getResume()) : 'Pas de CV disponible';
    }


}
