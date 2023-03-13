<?php

namespace App\Entity;

use App\Repository\ACRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ACRepository::class)]
class AC
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $competence = null;

    #[ORM\Column]
    private ?int $niveau = null;

    #[ORM\ManyToOne(inversedBy: 'idAC')]
    private ?Lier $idLier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCompetence(): ?string
    {
        return $this->competence;
    }

    public function setCompetence(string $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(int $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getIdLier(): ?Lier
    {
        return $this->idLier;
    }

    public function setIdLier(?Lier $idLier): self
    {
        $this->idLier = $idLier;

        return $this;
    }
}
