<?php
declare(strict_types=1);

namespace App\Controller;

use App\Application\Empresa\RegisterEmpresa\RegisterEmpresaHandler;
use App\Application\Empresa\RegisterEmpresa\RegisterEmpresaInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmpresaController extends AbstractController
{
    private readonly RegisterEmpresaHandler $registerEmpresaHandler;

    private readonly ValidatorInterface $validator;

    public function __construct(RegisterEmpresaHandler $registerEmpresaHandler, ValidatorInterface $validator)
    {
        $this->registerEmpresaHandler = $registerEmpresaHandler;
        $this->validator = $validator;
    }

    #[Route('/empresa', name: 'app_empresa_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $input = new RegisterEmpresaInput(
            licenca: $data['licenca'] ?? '',
            tokenIntegracao: $data['token_integracao'] ?? '',
        );

        $violations = $this->validator->validate($input);

        if ($violations->count() > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse([
                'errors' => $errors,
                'message' => 'Validation failed',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $result = $this->registerEmpresaHandler->handle($input);
        } catch (HttpException $e) {
            return new JsonResponse(
                [
                    'message' => $e->getMessage(),
                    'status' => $e->getStatusCode(),
                ],
                $e->getStatusCode(),
            );
        }

        return new JsonResponse($result->toArray(), Response::HTTP_CREATED);
    }
}
