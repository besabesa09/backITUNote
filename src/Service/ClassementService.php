<?php

namespace App\Service;

use App\Repository\EtudiantRepository;
use App\Entity\Classement;
use App\Entity\Bulletin;

class ClassementService
{
    private $etudiantRepository;
    private $bulletinService;

    public function __construct(EtudiantRepository $etudiantRepository, BulletinService $bulletinService)
    {
        $this->etudiantRepository = $etudiantRepository;
        $this->bulletinService = $bulletinService;
    }

    public function getClassementParSemestre(string $semestre): array
    {
        $etudiants = $this->etudiantRepository->findAll();

        $classement = [];

        foreach ($etudiants as $etudiant) {
            $bulletin = $this->bulletinService->getBulletin($etudiant->getEtu(), $semestre);

            if ($bulletin) {
                $moyenne = $bulletin->getMoyenne();

                $classement[] = [
                    'moyenne' => $moyenne,
                    'bulletin' => $bulletin
                ];
            }
        }

        usort($classement, function ($a, $b) {
            return $b['moyenne'] <=> $a['moyenne'];
        });

        $rangClassement = [];
        $rank = 1;

        foreach ($classement as $item) {
            $bulletin = $item['bulletin'];
            $rangClassement[] = new Classement($rank, $bulletin);
            $rank++;
        }

        return $rangClassement;
    }
}
