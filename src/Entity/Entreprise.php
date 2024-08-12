<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
#[ORM\Table(name: 'entreprise', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'unique_ninea', columns: ['ninea'])
])]

class Entreprise

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner ce champs")]
    #[Assert\Length(min:2, max:35, minMessage: "Le nom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères")]
    #[Assert\Regex(pattern:"/^[a-zA-ZÀ-ÿ\s'-]+$/", message:"Le nom ne peut contenir que des lettres et des espaces")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner ce champs")]
    #[Assert\Length(min:4, max: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: " Veuillez renseigner ce champs")]
    #[Assert\Length(
        min: 10,
        max: 10,
        exactMessage: 'Le NINEA doit comporter exactement 10 caractères.'
    )]
    private ?string $ninea = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    #[Assert\NotBlank(message: " Veuillez renseigner ce champs")]
    #[Assert\PositiveOrZero(message: 'Le chiffre d\'affaires doit être un nombre positif ou zéro.')]
    private ?string $chifAffaire = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?TypeEntreprise $typeEntreprise = null;

    #[ORM\OneToMany(targetEntity: Tache::class, mappedBy: 'entreprise')]
    private Collection $roles;
    #[ORM\Column(length: 255)]
    private ?string $responsable = null;

    #[ORM\OneToMany(targetEntity: Acteur::class, mappedBy: 'entreprise')]
    private Collection $acteurs;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->acteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): static
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getChifAffaire(): ?string
    {
        return $this->chifAffaire;
    }

    public function setChifAffaire(string $chifAffaire): static
    {
        $this->chifAffaire = $chifAffaire;

        return $this;
    }

    public function getTypeEntreprise(): ?TypeEntreprise
    {
        return $this->typeEntreprise;
    }

    public function setTypeEntreprise(?TypeEntreprise $typeEntreprise): static
    {
        $this->typeEntreprise = $typeEntreprise;

        return $this;
    }

    /**
     * @return Collection<int, Tache>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Tache $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(string $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * @return Collection<int, Acteur>
     */
    public function getActeurs(): Collection
    {
        return $this->acteurs;
    }

    public function addActeur(Acteur $acteur): static
    {
        if (!$this->acteurs->contains($acteur)) {
            $this->acteurs->add($acteur);
            $acteur->setEntreprise($this);
        }

        return $this;
    }

    public function removeActeur(Acteur $acteur): static
    {
        if ($this->acteurs->removeElement($acteur)) {
            // set the owning side to null (unless already changed)
            if ($acteur->getEntreprise() === $this) {
                $acteur->setEntreprise(null);
            }
        }

        return $this;
    }

}
