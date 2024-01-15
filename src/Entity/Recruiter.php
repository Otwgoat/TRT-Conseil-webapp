<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecruiterRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecruiterRepository::class)]
class Recruiter extends User
{
    #[ORM\Column(length: 255)]
    #[Groups(['getUsers', 'getRequests', 'getJobAdvertissements'])]
    private ?string $companyName = null;

    #[ORM\Column(length: 355)]
    #[Groups(['getUsers', 'getRequests'])]
    private ?string $companyAdress = null;

    #[ORM\Column]
    #[Groups(['getUsers', 'getRequests'])]
    private ?bool $approved = null;



    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyAdress(): ?string
    {
        return $this->companyAdress;
    }

    public function setCompanyAdress(string $companyAdress): static
    {
        $this->companyAdress = $companyAdress;

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
