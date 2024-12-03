<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "matiere")]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['matieres.show'])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50, unique: true)]
    #[Groups(['matieres.show', 'matieres.create', 'matieres.update'])]
    private ?string $codeMatiere = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(['matieres.show', 'matieres.create', 'matieres.update'])]
    private ?string $nom = null;

    #[ORM\Column(type: "integer")]
    #[Groups(['matieres.show', 'matieres.create', 'matieres.update'])]
    private ?int $credit = null;

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeMatiere(): ?string
    {
        return $this->codeMatiere;
    }

    public function setCodeMatiere(string $codeMatiere): self
    {
        $this->codeMatiere = $codeMatiere;
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

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setCredit(int $credit): self
    {
        $this->credit = $credit;
        return $this;
    }
}
