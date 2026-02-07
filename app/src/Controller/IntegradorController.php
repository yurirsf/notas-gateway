<?php
declare(strict_types=1);

namespace App\Controller;

use App\Application\Integrador\ScheduleIntegrador\IntegradorPayloadInput;
use App\Application\Integrador\ScheduleIntegrador\ScheduleIntegradorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IntegradorController extends AbstractController
{
    private readonly ScheduleIntegradorHandler $scheduleIntegradorHandler;

    private readonly ValidatorInterface $validator;

    public function __construct(ScheduleIntegradorHandler $scheduleIntegradorHandler, ValidatorInterface $validator)
    {
        $this->scheduleIntegradorHandler = $scheduleIntegradorHandler;
        $this->validator = $validator;
    }

    #[Route('/integrador', name: 'app_integrador_schedule', methods: ['POST'])]
    public function schedule(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $input = new IntegradorPayloadInput(
            tipo: $data['tipo'] ?? '',
            empresaId: $data['empresaId'] ?? '',
            nfeIdExterno: $data['nfeIdExterno'] ?? null,
            nfeId: $data['nfeId'] ?? null,
            nfeStatus: $data['nfeStatus'] ?? null,
            nfeMotivoStatus: $data['nfeMotivoStatus'] ?? null,
            nfeLinkPdf: $data['nfeLinkPdf'] ?? null,
            nfeLinkXml: $data['nfeLinkXml'] ?? null,
            nfeNumero: $data['nfeNumero'] ?? null,
            nfeCodigoVerificacao: $data['nfeCodigoVerificacao'] ?? null,
            nfeNumeroRps: $data['nfeNumeroRps'] ?? null,
            nfeSerieRps: $data['nfeSerieRps'] ?? null,
            nfeDataCompetencia: $data['nfeDataCompetencia'] ?? null,
        );

        $violations = $this->validator->validate($input);

        if ($violations->count() > 0) {
            $errors = [];

            foreach ($violations as $v) {
                $errors[$v->getPropertyPath()] = $v->getMessage();
            }

            return new JsonResponse(
                [
                    'errors' => $errors,
                    'message' => 'Validação falhou',
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $this->scheduleIntegradorHandler->handle($input);

        return new JsonResponse(
            [
                'message' => 'Aceito',
                'status' => Response::HTTP_ACCEPTED,
            ],
            Response::HTTP_ACCEPTED,
        );
    }
}
