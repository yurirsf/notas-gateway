<?php
declare(strict_types=1);

namespace App\Application\Empresa\RegisterEmpresa;

use App\Domain\Entity\Licenca;
use App\Domain\Repository\LicencaRepositoryInterface;
use Ramsey\Uuid\Uuid;

final class RegisterEmpresaHandler
{
    public function __construct(
        private readonly LicencaRepositoryInterface $licencaRepository,
    ) {
    }

    public function handle(RegisterEmpresaInput $input): RegisterEmpresaResult
    {
        $existingByLicenca = $this->licencaRepository->findByLicenca($input->licenca);

        if ($existingByLicenca !== null) {
            throw new LicencaAlreadyRegisteredException(
                sprintf('Já existe uma licença registrada com o identificador "%s".', $input->licenca),
            );
        }

        $existingByToken = $this->licencaRepository->findByTokenIntegracao($input->tokenIntegracao);

        if ($existingByToken !== null) {
            throw new TokenIntegracaoAlreadyUsedException(
                'O token_integracao informado já está em uso.',
            );
        }

        $id = Uuid::uuid4();
        $tokenIntegracao = Uuid::fromString($input->tokenIntegracao);
        $licenca = new Licenca($id, $input->licenca, $tokenIntegracao);

        $this->licencaRepository->save($licenca);

        return new RegisterEmpresaResult(
            id: $id->toString(),
            licenca: $licenca->getLicenca(),
            tokenIntegracao: $licenca->getTokenIntegracao()->toString()
        );
    }
}
