<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'idLieu', targetEntity: LieuElementary::class)]
    private Collection $lieuElementaries;

    public function __construct()
    {
        $this->lieuElementaries = new ArrayCollection();
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

    /**
     * @return Collection<int, LieuElementary>
     */
    public function getLieuElementaries(): Collection
    {
        return $this->lieuElementaries;
    }

    public function addLieuElementary(LieuElementary $lieuElementary): static
    {
        if (!$this->lieuElementaries->contains($lieuElementary)) {
            $this->lieuElementaries->add($lieuElementary);
            $lieuElementary->setIdLieu($this);
        }

        return $this;
    }

    public function removeLieuElementary(LieuElementary $lieuElementary): static
    {
        if ($this->lieuElementaries->removeElement($lieuElementary)) {
            // set the owning side to null (unless already changed)
            if ($lieuElementary->getIdLieu() === $this) {
                $lieuElementary->setIdLieu(null);
            }
        }

        return $this;
    }
}
