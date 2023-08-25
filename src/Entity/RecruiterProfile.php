<?php

namespace App\Entity;

use App\Repository\RecruiterProfileRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecruiterProfileRepository::class)]
class RecruiterProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'recruiterProfiles')]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private ?User $user;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $companyName = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $companyAddress = null;

    #[ORM\OneToMany(mappedBy: "recruiterID", targetEntity: "App\Entity\JobOffer")]
    private Collection $jobOffers;

    public function __construct()
    {
        $this->jobOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyAddress(): ?string
    {
        return $this->companyAddress;
    }

    public function setCompanyAddress(?string $companyAddress): static
    {
        $this->companyAddress = $companyAddress;

        return $this;
    }

    public function getJobOffers(): Collection
    {
        return $this->jobOffers;
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
}
