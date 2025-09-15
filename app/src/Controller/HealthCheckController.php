<?php
declare(strict_types=1);

namespace App\Controller;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController extends AbstractController
{
    #[Route('/healthcheck', name: 'app_healthcheck', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the health check of API',
        content: new OA\JsonContent(
            example: new OA\Schema(
                schema: 'Healthcheck',
                type: 'object',
                properties: [
                    new OA\Property(property: 'status', type: 'integer'),
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(property: 'timestamp', type: 'date-time'),
                ]
            )
        )
    )]
    public function index(): Response
    {
        return $this->json([
            'message' => 'I\'m OK!',
            'status' => 200,
            'timestamp' => (new \DateTime('now'))->format(\DateTime::ISO8601),
        ], 200);
    }
}
