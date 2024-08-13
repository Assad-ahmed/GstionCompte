<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;





    #[ORM\ManyToOne(inversedBy: 'roles')]
    private ?Acteur $acteur = null;

    #[ORM\Column]
    private ?bool $estComplet = false;

    #[ORM\ManyToOne(inversedBy: 'rolesUsers')]
    private ?User $user = null;


    #[ORM\Column(length: 255)]
    private ?string $commentaire = null;


    #[ORM\Column(length: 255)]
    private ?string $dureeTache = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRealisation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin = null;




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



    public function getActeur(): ?Acteur
    {
        return $this->acteur;
    }

    public function setActeur(?Acteur $acteur): static
    {
        $this->acteur = $acteur;

        return $this;
    }

    public function isEstComplet(): ?bool
    {
        return $this->estComplet;
    }

    public function setEstComplet(bool $estComplet): static
    {
        $this->estComplet = $estComplet;

        return $this;
    }
    public function __toString(): string
    {
        return $this->nom."".$this->estComplet;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }



    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

  public function getEtatCouleur(): ?string
    {
        $now = new \DateTime();
        $dateAvecDuree = $this->getDateAvecDuree();

        if ($this->dateRealisation && ($this->dateRealisation <= $now || $this->date_fin<=$now) ) {
            return 'clos'; // Terminé
        }

        // Vérifie si la tâche est en cours
        if ($this->date_fin && $this->date_fin > $now) {
            return 'en cours'; // En cours
        }

        // Si la tâche n'a pas encore commencé
        if ($this->dateRealisation && $this->dateRealisation > $now)
        {
            return 'non commencé';
        }
        // Si la tâche n'a pas applique
        return 'non applicable';

    }


    public function getDureeTache(): ?string
    {
        return $this->dureeTache;
    }

    public function setDureeTache(string $dureeTache): static
    {
        $this->dureeTache = $dureeTache;

        return $this;
    }

    public function getDateAvecDuree(): ?\DateTime
    {
        $currentDate = new \DateTime();
        $interval = $this->parseDuree($this->dureeTache);

        if ($interval === null) {
            return null;
        }

        $date = clone $currentDate;
        $date->add($interval);

        return $date;
    }

    private function parseDuree(string $duree): ?\DateInterval
    {
        if (preg_match('/^J([+-]\d+)$/', $duree, $matches)) {
            $jours = (int)$matches[1];
            return new \DateInterval('P' . abs($jours) . 'D');
        }
        return null;
    }

   public function getDateRealisation(): ?\DateTimeInterface
   {
       return $this->dateRealisation;
   }

   public function setDateRealisation(?\DateTimeInterface $dateRealisation): static
   {
       $this->dateRealisation = $dateRealisation;

       return $this;
   }

   public function getDateFin(): ?\DateTimeInterface
   {
       return $this->date_fin;
   }

   public function setDateFin(?\DateTimeInterface $date_fin): static
   {
       $this->date_fin = $date_fin;

       return $this;
   }


}
