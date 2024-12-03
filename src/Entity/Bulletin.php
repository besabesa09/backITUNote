<?php

namespace App\Entity;

class Bulletin
{
    private $etudiantId;
    private $etudiantNom;
    private $semestre;
    private $matieres = [];
    private $moyenne;
    private $totalCredits;

    public function __construct(string $etudiantId, string $etudiantNom, string $semestre)
    {
        $this->etudiantId = $etudiantId;
        $this->etudiantNom = $etudiantNom;
        $this->semestre = $semestre;
        $this->totalCredits = 0;
        $this->moyenne = 0;
    }

    public function addMatiere(string $nom, float $note, int $credit, string $examenSession, bool $estPresente): void
    {
        if (!$estPresente) {
            //throw new \Exception("Incomplete data: No note available for this exam.");
        }

        $this->matieres[] = [
            'nom' => $nom,
            'note' => $note,
            'credit' => $credit,
            'examen_session' => $examenSession,
            'remarque' => $this->getRemarque($note)
        ];

        $this->totalCredits += $credit;
        $this->moyenne += $note * $credit;
    }

    public function getMoyenne(): float
    {
        $totalNotes = array_sum(array_column($this->matieres, 'note'));
        $nombreMatieres = count($this->matieres);

        return $nombreMatieres > 0 ? $totalNotes / $nombreMatieres : 0;
    }

    public function getBulletinData(): array
    {
        $this->makeCpl();
        return [
            'etudiant_id' => $this->etudiantId,
            'etudiant_nom' => $this->etudiantNom,
            'semestre' => $this->semestre,
            'matieres' => $this->matieres,
            'moyenne' => $this->getMoyenne(),
            'total_credits' => $this->totalCredits
        ];
    }

    public function makeCpl(): void
    {
        $m = 0;

        foreach ($this->matieres as &$matiere) {
            $note = $matiere['note'];
            $matiere['remarque'] = $this->getRemarque($note);

            if ($note < 6) {
                $matiere['credit'] = 0;
            }

            if ($note < 10) {
                $m++;
            }
        }

        foreach ($this->matieres as &$matiere) {
            if ($matiere['note'] >= 6 && $matiere['note'] < 9) {
                if ($m > 2) {
                    $matiere['remarque'] = 'Ajourné';
                    $matiere['credit'] = 0;
                } else {
                    $matiere['remarque'] = 'Compensé';
                }
            }
        }

        $this->updateCredit();
    }


    private function updateCredit(): void
    {
        $totalCredits = 0;

        foreach ($this->matieres as $matiere) {
            $totalCredits += $matiere['credit'];
        }

        $this->totalCredits = $totalCredits;
    }

    private function getRemarque(float $note): string
    {
        if ($note < 6) {
            return 'Ajourné';
        } elseif ($note >= 16) {
            return 'Très bien';
        } elseif ($note >= 14) {
            return 'Bien';
        } elseif ($note >= 12) {
            return 'Assez bien';
        } elseif ($note >= 10) {
            return 'Passable';
        } else {
            return 'Insuffisant';
        }
    }

}
