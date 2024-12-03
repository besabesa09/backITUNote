<?php

namespace App\Controller\API;

use App\Service\BulletinService;
use App\Service\ClassementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BulletinApiController extends AbstractController
{
    private $bulletinService;
    private $classementService;

    public function __construct(BulletinService $bulletinService, ClassementService $classementService)
    {
        $this->bulletinService = $bulletinService;
        $this->classementService = $classementService;
    }

    #[Route('/api/bulletin/{etu}/{sem}', name: 'api_bulletin', methods: ['GET'])]
    public function getBulletin(string $etu, string $sem): JsonResponse
    {
        $bulletin = $this->bulletinService->getBulletin($etu, $sem);

        if (!$bulletin) {
            return $this->json(['error' => 'Bulletin not found'], 404); // Si aucun bulletin n'est trouvé
        }

        return $this->json($bulletin->getBulletinData());
    }

    #[Route('/api/classement/{sem}', name: 'api_classement', methods: ['GET'])]
    public function getClassementParSemestre(string $sem): JsonResponse
    {
        return $this->json($this->classementService->getClassementParSemestre($sem));
    }
}
