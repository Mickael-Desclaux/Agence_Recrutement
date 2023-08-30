<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private string $role;

    /**
     * @var Application[]|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: "candidate", targetEntity: Application::class)]
    private $applications;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(
        min: 8,
        minMessage: "Votre mot de passe doit contenir au minimum {{ limit }} caractères"
    )]
    #[Assert\Regex(
        pattern: "/[a-z]/",
        message: "Le mot de passe doit contenir au moins une lettre minuscule"
    )]
    #[Assert\Regex(
        pattern: "/[A-Z]/",
        message: "Le mot de passe doit contenir au moins une lettre majuscule"
    )]
    #[Assert\Regex(
        pattern: "/[0-9]/",
        message: "Le mot de passe doit contenir au moins un chiffre"
    )]
    private ?string $password = null;

    #[ORM\Column]
    private bool $userValidation = false;

    public function isUserValidation(): bool
    {
        return $this->userValidation;
    }

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: JobOffer::class)]
    private $jobOffers;

    /**
     * @var RecruiterProfile[]|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: RecruiterProfile::class)]
    private $recruiterProfiles;

    /**
     * @var CandidateProfile[]|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CandidateProfile::class)]
    private $candidateProfiles;

    public function __construct() {
        $this->recruiterProfiles = new ArrayCollection();
        $this->candidateProfiles = new ArrayCollection();
        $this->jobOffers = new ArrayCollection();
    }

    public function getCandidateProfiles(): ArrayCollection
    {
        return $this->candidateProfiles;
    }

    public function addCandidateProfile(CandidateProfile $candidateProfile): self
    {
        if (!$this->candidateProfiles->contains($candidateProfile)) {
            $this->candidateProfiles[] = $candidateProfile;
            $candidateProfile->setUser($this);
        }

        return $this;
    }

    public function setUserValidation(bool $userValidation): void
    {
        $this->userValidation = $userValidation;
    }

    public function getRecruiterProfiles(): ArrayCollection
    {
        return $this->recruiterProfiles;
    }

    public function addRecruiterProfile(RecruiterProfile $recruiterProfile): self
    {
        if (!$this->recruiterProfiles->contains($recruiterProfile)) {
            $this->recruiterProfiles[] = $recruiterProfile;
            $recruiterProfile->setUser($this);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getJobOffers()
    {
        return $this->jobOffers;
    }

    public function addJobOffer(JobOffer $jobOffer): self
    {
        if (!$this->jobOffers->contains($jobOffer)) {
            $this->jobOffers[] = $jobOffer;
            $jobOffer->setUser($this);
        }

        return $this;
    }

    public function removeJobOffer(JobOffer $jobOffer): self
    {
        if ($this->jobOffers->removeElement($jobOffer)) {
            // set the owning side to null (unless already changed)
            if ($jobOffer->getUser() === $this) {
                $jobOffer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Application[]|ArrayCollection
     */
    public function getApplications(): array|ArrayCollection
    {
        return $this->applications;
    }

    /**
     * @param Application[]|ArrayCollection $applications
     */
    public function setApplications(array|ArrayCollection $applications): void
    {
        $this->applications = $applications;
    }
}
