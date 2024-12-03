<?php

namespace App\Service;

use App\Entity\Bulletin;
use Doctrine\DBAL\Connection;

class BulletinService
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getBulletin(string $etu, string $sem): Bulletin
    {
        $sql = "
            SELECT
                examen_id,
                examen_session,
                code_matiere,
                sem,
                etudiant_id,
                etudiant_nom,
                matiere_nom,
                credit,
                note,
                estPresente
            FROM vue_notes_etudiants
            WHERE etudiant_id = :etu AND sem = :sem
        ";

        $stmt = $this->connection->executeQuery($sql, ['etu' => $etu, 'sem' => $sem]);
        $results = $stmt->fetchAllAssociative();

        if (empty($results)) {
            throw new \Exception("No results found for the given student and semester.");
        }

        $bulletin = new Bulletin($etu, $results[0]['etudiant_nom'], $sem);

        foreach ($results as $row) {
            $bulletin->addMatiere(
                $row['matiere_nom'],
                $row['note'],
                $row['credit'],
                $row['examen_session'],
                $row['estpresente']
            );
        }


        return $bulletin;
    }
}
