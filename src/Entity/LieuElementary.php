<?php

namespace App\Entity;

use App\Repository\LieuElementaryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LieuElementaryRepository::class)]
class LieuElementary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lieuElementaries')]
    private ?Lieu $idLieu = null;

    #[ORM\ManyToOne(inversedBy: 'lieuElementaries')]
    private ?Elementary $idElementary = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdLieu(): ?Lieu
    {
        return $this->idLieu;
    }

    public function setIdLieu(?Lieu $idLieu): static
    {
        $this->idLieu = $idLieu;

        return $this;
    }

    public function getIdElementary(): ?Elementary
    {
        return $this->idElementary;
    }

    public function setIdElementary(?Elementary $idElementary): static
    {
        $this->idElementary = $idElementary;

        return $this;
    }
}
