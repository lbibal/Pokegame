<?php

namespace App\Entity;

use App\Repository\CommerceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommerceRepository::class)]
class Commerce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dateAcheter = null;

    #[ORM\ManyToOne(inversedBy: 'commerces')]
    private ?PokemonUser $idPokemonUser = null;

    #[ORM\ManyToOne(inversedBy: 'commerces')]
    private ?User $idUserAcheteur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDateAcheter(): ?string
    {
        return $this->dateAcheter;
    }

    public function setDateAcheter(?string $dateAcheter): static
    {
        $this->dateAcheter = $dateAcheter;

        return $this;
    }

    public function getIdPokemonUser(): ?PokemonUser
    {
        return $this->idPokemonUser;
    }

    public function setIdPokemonUser(?PokemonUser $idPokemonUser): static
    {
        $this->idPokemonUser = $idPokemonUser;

        return $this;
    }

    public function getIdUserAcheteur(): ?User
    {
        return $this->idUserAcheteur;
    }

    public function setIdUserAcheteur(?User $idUserAcheteur): static
    {
        $this->idUserAcheteur = $idUserAcheteur;

        return $this;
    }
}
