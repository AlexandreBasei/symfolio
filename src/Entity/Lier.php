<?php

namespace App\Entity;

use App\Repository\LierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LierRepository::class)]
class Lier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'idLier')]
    private ?Projets $idProjet = null;

    #[ORM\OneToMany(mappedBy: 'idLier', targetEntity: AC::class)]
    private Collection $idAC;

    public function __construct()
    {
        $this->idAC = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProjet(): ?Projets
    {
        return $this->idProjet;
    }

    public function setIdProjet(?Projets $idProjet): self
    {
        $this->idProjet = $idProjet;

        return $this;
    }

    /**
     * @return Collection<int, AC>
     */
    public function getIdAC(): Collection
    {
        return $this->idAC;
    }

    public function addIdAC(AC $idAC): self
    {
        if (!$this->idAC->contains($idAC)) {
            $this->idAC->add($idAC);
            $idAC->setIdLier($this);
        }

        return $this;
    }

    public function removeIdAC(AC $idAC): self
    {
        if ($this->idAC->removeElement($idAC)) {
            // set the owning side to null (unless already changed)
            if ($idAC->getIdLier() === $this) {
                $idAC->setIdLier(null);
            }
        }

        return $this;
    }
}
