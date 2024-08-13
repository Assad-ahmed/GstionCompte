<?php

namespace App\Entity;

use App\Repository\TypeEntrepriseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypeEntrepriseRepository::class)]
#[ORM\Table(name: 'type_entreprise', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'unique_type', columns: ['type'])
])]
class TypeEntreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: " Veuillez renseigner ce champs")]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
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
    public function __toString(): string
    {
        return $this->type;
    }
}
