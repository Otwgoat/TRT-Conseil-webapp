<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ConsultantRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConsultantRepository::class)]
class Consultant extends User
{
    #[ORM\Column(length: 255)]
    #[Groups('getUsers')]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups('getUsers')]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    private ?string $firstName = null;



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
}
