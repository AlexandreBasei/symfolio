<?php

namespace App\Entity;

use App\Repository\ACRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Projets::class, mappedBy: 'idAC')]
    private Collection $idProjet;

    public function __construct()
    {
        $this->idProjet = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Projets>
     */
    public function getIdProjet(): Collection
    {
        return $this->idProjet;
    }

    public function addIdProjet(Projets $idProjet): self
    {
        if (!$this->idProjet->contains($idProjet)) {
            $this->idProjet->add($idProjet);
            $idProjet->addIdAC($this);
        }

        return $this;
    }

    public function removeIdProjet(Projets $idProjet): self
    {
        if ($this->idProjet->removeElement($idProjet)) {
            $idProjet->removeIdAC($this);
        }

        return $this;
    }
}
