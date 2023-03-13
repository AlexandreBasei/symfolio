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

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $tag = [];

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_publi = null;

    #[ORM\OneToMany(mappedBy: 'idProjet', targetEntity: Lier::class)]
    private Collection $idLier;

    #[ORM\OneToMany(mappedBy: 'idProjet', targetEntity: Noter::class)]
    private Collection $idNote;

    #[ORM\ManyToOne]
    private ?User $idUser = null;

    public function __construct()
    {
        $this->idLier = new ArrayCollection();
        $this->idNote = new ArrayCollection();
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

    public function getTag(): array
    {
        return $this->tag;
    }

    public function setTag(?array $tag): self
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
     * @return Collection<int, Lier>
     */
    public function getIdLier(): Collection
    {
        return $this->idLier;
    }

    public function addIdLier(Lier $idLier): self
    {
        if (!$this->idLier->contains($idLier)) {
            $this->idLier->add($idLier);
            $idLier->setIdProjet($this);
        }

        return $this;
    }

    public function removeIdLier(Lier $idLier): self
    {
        if ($this->idLier->removeElement($idLier)) {
            // set the owning side to null (unless already changed)
            if ($idLier->getIdProjet() === $this) {
                $idLier->setIdProjet(null);
            }
        }

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
}
