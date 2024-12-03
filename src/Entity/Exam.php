<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "examen")]
class Examen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["examen.show", "examen.list"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    #[Groups(["examen.show", "examen.list"])]
    private ?string $session = null;

    #[ORM\Column(type: "string", length: 50)]
    #[Groups(["examen.show", "examen.list"])]
    private ?string $codeMatiere = null;

    #[ORM\Column(type: "string", length: 10)]
    #[Groups(["examen.show", "examen.list"])]
    private ?string $sem = null;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?string
    {
        return $this->session;
    }

    public function setSession(string $session): self
    {
        $this->session = $session;
        return $this;
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

    public function getSem(): ?string
    {
        return $this->sem;
    }

    public function setSem(string $sem): self
    {
        $this->sem = $sem;
        return $this;
    }
}
