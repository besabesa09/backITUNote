<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "etudiant")]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['etudiants.show'])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50, unique: true)]
    #[Groups(['etudiants.show'])]
    private ?string $etu = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(['etudiants.show'])]
    private ?string $nom = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    #[Groups(['etudiants.show'])]
    private ?string $prom = null;

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtu(): ?string
    {
        return $this->etu;
    }

    public function setEtu(string $etu): self
    {
        $this->etu = $etu;
        return $this;
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

    public function getProm(): ?string
    {
        return $this->prom;
    }

    public function setProm(?string $prom): self
    {
        $this->prom = $prom;
        return $this;
    }
}
