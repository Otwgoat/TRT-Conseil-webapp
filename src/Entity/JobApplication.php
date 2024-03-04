<?php

namespace App\Entity;

use App\Repository\JobApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JobApplicationRepository::class)]
class JobApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getJobApplications', 'getJobApplyApproveRequests'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'jobApplications', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getJobApplications', 'getJobApplyApproveRequests'])]
    private ?JobAdvertissement $jobID = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getJobApplications', 'getJobApplyApproveRequests'])]
    private ?User $candidateID = null;

    #[ORM\Column]
    #[Groups(['getJobApplications'])]
    private ?bool $approved = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobID(): ?JobAdvertissement
    {
        return $this->jobID;
    }

    public function setJobID(?JobAdvertissement $jobID): static
    {
        $this->jobID = $jobID;

        return $this;
    }

    public function getCandidateID(): ?User
    {
        return $this->candidateID;
    }

    public function setCandidateID(?User $candidateID): static
    {
        $this->candidateID = $candidateID;

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
}
