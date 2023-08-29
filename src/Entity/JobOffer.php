<?php

namespace App\Entity;

use App\Repository\JobOfferRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobOfferRepository::class)]
class JobOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(length: 100)]
    private ?string $jobTitle = null;

    #[ORM\Column(length: 255)]
    private ?string $jobLocation = null;

    #[ORM\Column(length: 30)]
    private ?string $contractType = null;

    #[ORM\Column(length: 2000)]
    private ?string $jobDescription = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $candidateExperience = null;

    #[ORM\Column(length: 150)]
    private ?string $workingHours = null;

    #[ORM\Column(length: 50)]
    private ?string $salary = null;

    #[ORM\Column]
    private bool $publishValidation = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getJobLocation(): ?string
    {
        return $this->jobLocation;
    }

    public function setJobLocation(string $jobLocation): static
    {
        $this->jobLocation = $jobLocation;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(string $contractType): static
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getJobDescription(): ?string
    {
        return $this->jobDescription;
    }

    public function setJobDescription(string $jobDescription): static
    {
        $this->jobDescription = $jobDescription;

        return $this;
    }

    public function getCandidateExperience(): ?string
    {
        return $this->candidateExperience;
    }

    public function setCandidateExperience(?string $candidateExperience): static
    {
        $this->candidateExperience = $candidateExperience;

        return $this;
    }

    public function getWorkingHours(): ?string
    {
        return $this->workingHours;
    }

    public function setWorkingHours(string $workingHours): static
    {
        $this->workingHours = $workingHours;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function isPublishValidation(): ?bool
    {
        return $this->publishValidation;
    }

    public function setPublishValidation(bool $publishValidation): static
    {
        $this->publishValidation = $publishValidation;

        return $this;
    }
}
