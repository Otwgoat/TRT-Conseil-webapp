<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\JobApproveRequestRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JobApproveRequestRepository::class)]
class JobApproveRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('getJobApproveRequest')]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('getJobApproveRequest')]
    private ?JobAdvertissement $jobID = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $requestDate = null;

    #[ORM\Column]
    #[Groups('getJobApproveRequest')]
    private ?bool $approved = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobID(): ?JobAdvertissement
    {
        return $this->jobID;
    }

    public function setJobID(JobAdvertissement $jobID): static
    {
        $this->jobID = $jobID;

        return $this;
    }

    public function getRequestDate(): ?\DateTimeInterface
    {
        return $this->requestDate;
    }

    public function setRequestDate(?\DateTimeInterface $requestDate): static
    {
        $this->requestDate = $requestDate;

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
