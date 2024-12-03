<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class Utilitaire
{
    public static function createJsonResponse(bool $success, $data = null, $error = null, int $statusCode = 200): JsonResponse
    {
        return new JsonResponse([
            'success' => $success,
            'data' => $data,
            'error' => $error,
        ], $statusCode);
    }
}
?>
