<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: '`admin`')]
class Admin extends User
{
    #[ORM\Column(length: 255)]
    #[Groups('getUsers')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups('getUsers')]
    private ?string $lastName = null;




    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }
}
