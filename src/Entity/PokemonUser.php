<?php

namespace App\Entity;

use App\Repository\PokemonUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonUserRepository::class)]
class PokemonUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $LastTrainingTime = null;

    #[ORM\Column(nullable: true)]
    private ?float $xpGain = null;

    #[ORM\Column(nullable: true)]
    private ?float $xpMax = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonUsers')]
    private ?User $idUser = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonUsers')]
    private ?Pokemon $idPokemon = null;

    #[ORM\Column]
    private ?int $niveau = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastTrainingTime(): ?\DateTimeInterface
    {
        return $this->LastTrainingTime;
    }

    public function setLastTrainingTime(?\DateTimeInterface $LastTrainingTime): static
    {
        $this->LastTrainingTime = $LastTrainingTime;

        return $this;
    }

    public function getXpGain(): ?float
    {
        return $this->xpGain;
    }

    public function setXpGain(?float $xpGain): static
    {
        $this->xpGain = $xpGain;

        return $this;
    }

    public function getXpMax(): ?float
    {
        return $this->xpMax;
    }

    public function setXpMax(?float $xpMax): static
    {
        $this->xpMax = $xpMax;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdPokemon(): ?Pokemon
    {
        return $this->idPokemon;
    }

    public function setIdPokemon(?Pokemon $idPokemon): static
    {
        $this->idPokemon = $idPokemon;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(int $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }
}
