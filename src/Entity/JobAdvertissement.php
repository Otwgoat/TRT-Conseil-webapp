<?php

namespace App\Entity;

use App\Repository\JobAdvertissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JobAdvertissementRepository::class)]
class JobAdvertissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getJobAdvertissements', 'getJobApproveRequest', 'getJobApplications'])]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre de l'annonce est obligatoire")]
    #[Groups(['getJobAdvertissements', 'getJobApproveRequest', 'getJobApplications', 'getJobApplyApproveRequests'])]
    private ?string $title = null;

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: "La description de l'annonce est obligatoire")]
    #[Groups(['getJobAdvertissements', 'getJobApproveRequest', 'getJobApplyApproveRequests'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le lieu de travail est obligatoire")]
    #[Groups(['getJobAdvertissements', 'getJobApproveRequest', 'getJobApplications', 'getJobApplyApproveRequests'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nombre d'heures est obligatoire")]
    #[Groups(['getJobAdvertissements', 'getJobApproveRequest', 'getJobApplications'])]
    private ?string $planning = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le salaire est obligatoire")]
    #[Assert\Positive(message: "Le salaire doit être un nombre positif")]
    #[Groups(['getJobAdvertissements', 'getJobApproveRequest', 'getJobApplications'])]
    private ?int $salary = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'jobApplications')]
    #[Groups('getJobAdvertissements')]
    private Collection $candidateId;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getJobAdvertissements', 'getJobApproveRequest', 'getJobApplications', 'getJobApplyApproveRequests'])]
    private ?User $recruiterId = null;

    #[ORM\Column]
    #[Groups('getJobAdvertissements')]
    private ?bool $approved = null;

    #[ORM\OneToMany(mappedBy: 'jobID', targetEntity: JobApplication::class, orphanRemoval: true)]
    private Collection $jobApplications;

    #[ORM\Column(length: 255)]
    #[Groups(['getJobAdvertissements', 'getJobApproveRequest', 'getJobApplications'])]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de début est obligatoire")]
    #[Groups(['getJobAdvertissements', 'getJobApproveRequest', 'getJobApplications'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['getJobAdvertissements', 'getJobApproveRequest', 'getJobApplications'])]
    private ?\DateTimeInterface $endDate = null;

    public function __construct()
    {
        $this->candidateId = new ArrayCollection();
        $this->jobApplications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPlanning(): ?string
    {
        return $this->planning;
    }

    public function setPlanning(string $planning): static
    {
        $this->planning = $planning;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCandidateId(): Collection
    {
        return $this->candidateId;
    }

    public function addCandidateId(User $candidateId): static
    {
        if (!$this->candidateId->contains($candidateId)) {
            $this->candidateId->add($candidateId);
        }

        return $this;
    }

    public function removeCandidateId(User $candidateId): static
    {
        $this->candidateId->removeElement($candidateId);

        return $this;
    }

    public function getRecruiterId(): ?User
    {
        return $this->recruiterId;
    }

    public function setRecruiterId(?User $recruiterId): static
    {
        $this->recruiterId = $recruiterId;

        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): static
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * @return Collection<int, JobApplication>
     */
    public function getJobApplications(): Collection
    {
        return $this->jobApplications;
    }

    public function addJobApplication(JobApplication $jobApplication): static
    {
        if (!$this->jobApplications->contains($jobApplication)) {
            $this->jobApplications->add($jobApplication);
            $jobApplication->setJobID($this);
        }

        return $this;
    }

    public function removeJobApplication(JobApplication $jobApplication): static
    {
        if ($this->jobApplications->removeElement($jobApplication)) {
            // set the owning side to null (unless already changed)
            if ($jobApplication->getJobID() === $this) {
                $jobApplication->setJobID(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}
