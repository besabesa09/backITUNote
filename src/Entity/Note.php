<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "note")]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["note.show", "note.list"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    #[Groups(["note.show", "note.list"])]
    private ?string $etu = null;

    #[ORM\Column(type: "float")]
    #[Groups(["note.show", "note.list"])]
    private ?float $value = null;

    #[ORM\ManyToOne(targetEntity: Examen::class)]
    #[ORM\JoinColumn(name: "id_exam", referencedColumnName: "id", nullable: false)]
    #[Groups(["note.show", "note.list"])]
    private ?Examen $examen = null;

    // Getters and Setters

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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getExamen(): ?Examen
    {
        return $this->examen;
    }

    public function setExamen(Examen $examen): self
    {
        $this->examen = $examen;
        return $this;
    }
}
