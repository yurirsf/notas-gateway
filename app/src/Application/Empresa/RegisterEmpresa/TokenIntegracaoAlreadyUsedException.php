<?php
declare(strict_types=1);

namespace App\Application\Empresa\RegisterEmpresa;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class TokenIntegracaoAlreadyUsedException extends BadRequestHttpException
{
}
