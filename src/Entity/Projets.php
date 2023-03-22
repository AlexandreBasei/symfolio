<?php

namespace App\Entity;

use App\Repository\ProjetsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetsRepository::class)]
class Projets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $tag = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_publi = null;

    #[ORM\OneToMany(mappedBy: 'idProjet', targetEntity: Noter::class)]
    private Collection $idNote;

    #[ORM\ManyToOne]
    private ?User $idUser = null;

    #[ORM\ManyToMany(targetEntity: AC::class, inversedBy: 'idProjet')]
    private Collection $idAC;

    public function __construct()
    {
        $this->idNote = new ArrayCollection();
        $this->idAC = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getDatePubli(): ?\DateTimeInterface
    {
        return $this->date_publi;
    }

    public function setDatePubli(\DateTimeInterface $date_publi): self
    {
        $this->date_publi = $date_publi;

        return $this;
    }

    /**
     * @return Collection<int, Noter>
     */
    public function getIdNote(): Collection
    {
        return $this->idNote;
    }

    public function addIdNote(Noter $idNote): self
    {
        if (!$this->idNote->contains($idNote)) {
            $this->idNote->add($idNote);
            $idNote->setIdProjet($this);
        }

        return $this;
    }

    public function removeIdNote(Noter $idNote): self
    {
        if ($this->idNote->removeElement($idNote)) {
            // set the owning side to null (unless already changed)
            if ($idNote->getIdProjet() === $this) {
                $idNote->setIdProjet(null);
            }
        }

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

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
        }

        return $this;
    }

    public function removeIdAC(AC $idAC): self
    {
        $this->idAC->removeElement($idAC);

        return $this;
    }
}