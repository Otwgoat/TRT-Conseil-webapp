<?php

namespace App\Entity;

use App\Entity\Admin;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap(['admin' => Admin::class, 'candidate' => Candidate::class, 'consultant' => Consultant::class, 'recruiter' => Recruiter::class])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getUsers', 'getRequests', 'getJobAdvertissements', 'getJobApplications'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "L'email n'est pas valide")]
    #[Groups(['getUsers', 'getRequests', 'getJobAdvertissements', 'getJobApplications', 'getJobApplyApproveRequests'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['getUsers', 'getRequests', 'getJobAdvertissements'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    #[Assert\Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', message: "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre")]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: JobAdvertissement::class, mappedBy: 'candidateId')]
    private Collection $jobApplications;

    #[ORM\OneToMany(mappedBy: 'candidateID', targetEntity: JobApplication::class, orphanRemoval: true)]
    private Collection $applications;

    public function __construct()
    {
        $this->jobApplications = new ArrayCollection();
        $this->applications = new ArrayCollection();
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
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

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

    /**
     * @return Collection<int, JobAdvertissement>
     */
    public function getJobApplications(): Collection
    {
        return $this->jobApplications;
    }

    public function addJobApplication(JobAdvertissement $jobApplication): static
    {
        if (!$this->jobApplications->contains($jobApplication)) {
            $this->jobApplications->add($jobApplication);
            $jobApplication->addCandidateId($this);
        }

        return $this;
    }

    public function removeJobApplication(JobAdvertissement $jobApplication): static
    {
        if ($this->jobApplications->removeElement($jobApplication)) {
            $jobApplication->removeCandidateId($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, JobApplication>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(JobApplication $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setCandidateID($this);
        }

        return $this;
    }

    public function removeApplication(JobApplication $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getCandidateID() === $this) {
                $application->setCandidateID(null);
            }
        }

        return $this;
    }
}
