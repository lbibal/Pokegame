<?php

namespace App\Entity;

use App\Repository\ElementaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElementaryRepository::class)]
class Elementary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'type1', targetEntity: Pokemon::class)]
    private Collection $pokemon;

    #[ORM\OneToMany(mappedBy: 'type2', targetEntity: Pokemon::class)]
    private Collection $pokemon2;

    #[ORM\OneToMany(mappedBy: 'idElementary', targetEntity: LieuElementary::class)]
    private Collection $lieuElementaries;

    public function __construct()
    {
        $this->pokemon = new ArrayCollection();
        $this->pokemon2 = new ArrayCollection();
        $this->lieuElementaries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemon(): Collection
    {
        return $this->pokemon;
    }

    public function addPokemon(Pokemon $pokemon): static
    {
        if (!$this->pokemon->contains($pokemon)) {
            $this->pokemon->add($pokemon);
            $pokemon->setType1($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): static
    {
        if ($this->pokemon->removeElement($pokemon)) {
            // set the owning side to null (unless already changed)
            if ($pokemon->getType1() === $this) {
                $pokemon->setType1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemon2(): Collection
    {
        return $this->pokemon2;
    }

    public function addPokemon2(Pokemon $pokemon2): static
    {
        if (!$this->pokemon2->contains($pokemon2)) {
            $this->pokemon2->add($pokemon2);
            $pokemon2->setType2($this);
        }

        return $this;
    }

    public function removePokemon2(Pokemon $pokemon2): static
    {
        if ($this->pokemon2->removeElement($pokemon2)) {
            // set the owning side to null (unless already changed)
            if ($pokemon2->getType2() === $this) {
                $pokemon2->setType2(null);
            }
        }

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
            $lieuElementary->setIdElementary($this);
        }

        return $this;
    }

    public function removeLieuElementary(LieuElementary $lieuElementary): static
    {
        if ($this->lieuElementaries->removeElement($lieuElementary)) {
            // set the owning side to null (unless already changed)
            if ($lieuElementary->getIdElementary() === $this) {
                $lieuElementary->setIdElementary(null);
            }
        }

        return $this;
    }
}
