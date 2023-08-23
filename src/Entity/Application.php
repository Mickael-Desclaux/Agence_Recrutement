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

    #[ORM\Column]
    private ?int $candidateID = null;

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

    public function getCandidateID(): ?int
    {
        return $this->candidateID;
    }

    public function setCandidateID(int $candidateID): static
    {
        $this->candidateID = $candidateID;

        return $this;
    }

    public function isApplicationValidation(): ?bool
    {
        return $this->applicationValidation;
    }

    public function setApplicationValidation(bool $applicationValidation): static
    {
        $this->applicationValidation = $applicationValidation;

        return $this;
    }
}
