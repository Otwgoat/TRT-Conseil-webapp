<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CandidateRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CandidateRepository::class)]
class Candidate extends User
{
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    #[Groups(['getUsers', 'getRequests', 'getJobAdvertissements', 'getJobApplications', 'getJobApplyApproveRequests'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    #[Groups(['getUsers', 'getRequests', 'getJobAdvertissements', 'getJobApplications', 'getJobApplyApproveRequests'])]
    private ?string $firstName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups('getUsers')]
    #[Assert\NotBlank(message: "La date de naissance est obligatoire")]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le poste est obligatoire")]
    #[Groups(['getUsers', 'getRequests', 'getJobApplications', 'getJobApplyApproveRequests'])]
    private ?string $job = null;

    #[ORM\Column(length: 355, nullable: true)]
    #[Groups(['getUsers', 'getJobApplications'])]
    private ?string $cvPath = null;

    #[ORM\Column]
    #[Groups(['getUsers', 'getRequests'])]
    private ?bool $approved = null;





    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getCvPath(): ?string
    {
        return $this->cvPath;
    }

    public function setCvPath(?string $cvPath): static
    {
        $this->cvPath = $cvPath;

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
