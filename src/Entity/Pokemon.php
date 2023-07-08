<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?bool $evolution = null;

    #[ORM\Column]
    private ?bool $starter = null;

    #[ORM\Column(length: 1)]
    private ?string $typeCourbeNiveau = null;

    #[ORM\ManyToOne(inversedBy: 'pokemon')]
    private ?Elementary $type1 = null;

    #[ORM\ManyToOne(inversedBy: 'pokemon2')]
    private ?Elementary $type2 = null;

    #[ORM\OneToMany(mappedBy: 'idPokemon', targetEntity: PokemonUser::class)]
    private Collection $pokemonUsers;

    public function __construct()
    {
        $this->pokemonUsers = new ArrayCollection();
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

    public function isEvolution(): ?bool
    {
        return $this->evolution;
    }

    public function setEvolution(bool $evolution): static
    {
        $this->evolution = $evolution;

        return $this;
    }

    public function isStarter(): ?bool
    {
        return $this->starter;
    }

    public function setStarter(bool $starter): static
    {
        $this->starter = $starter;

        return $this;
    }

    public function getTypeCourbeNiveau(): ?string
    {
        return $this->typeCourbeNiveau;
    }

    public function setTypeCourbeNiveau(string $typeCourbeNiveau): static
    {
        $this->typeCourbeNiveau = $typeCourbeNiveau;

        return $this;
    }

    public function getType1(): ?Elementary
    {
        return $this->type1;
    }

    public function setType1(?Elementary $type1): static
    {
        $this->type1 = $type1;

        return $this;
    }

    public function getType2(): ?Elementary
    {
        return $this->type2;
    }

    public function setType2(?Elementary $type2): static
    {
        $this->type2 = $type2;

        return $this;
    }

    
    /**
     * @return Collection<int, PokemonUser>
     */
    public function getPokemonUsers(): Collection
    {
        return $this->pokemonUsers;
    }

    public function addPokemonUser(PokemonUser $pokemonUser): static
    {
        if (!$this->pokemonUsers->contains($pokemonUser)) {
            $this->pokemonUsers->add($pokemonUser);
            $pokemonUser->setIdPokemon($this);
        }

        return $this;
    }

    public function removePokemonUser(PokemonUser $pokemonUser): static
    {
        if ($this->pokemonUsers->removeElement($pokemonUser)) {
            // set the owning side to null (unless already changed)
            if ($pokemonUser->getIdPokemon() === $this) {
                $pokemonUser->setIdPokemon(null);
            }
        }

        return $this;
    }
}
