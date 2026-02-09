<?php
declare(strict_types=1);

namespace App\Application\Empresa\RegisterEmpresa;

use App\Domain\Entity\Licenca;
use App\Domain\Repository\LicencaRepositoryInterface;
use Ramsey\Uuid\Uuid;

final class RegisterEmpresaHandler
{
    private readonly LicencaRepositoryInterface $licencaRepository;

    public function __construct(LicencaRepositoryInterface $licencaRepository)
    {
        $this->licencaRepository = $licencaRepository;
    }

    public function handle(RegisterEmpresaInput $input): RegisterEmpresaResult
    {
        $existingByLicenca = $this->licencaRepository->findByLicenca($input->getLicenca());

        if ($existingByLicenca !== null) {
            throw new LicencaAlreadyRegisteredException(
                \sprintf('Já existe uma licença registrada com o identificador "%s".', $input->getLicenca())
            );
        }

        $existingByToken = $this->licencaRepository->findByTokenIntegracao($input->getTokenIntegracao());

        if ($existingByToken !== null) {
            throw new TokenIntegracaoAlreadyUsedException('O token_integracao informado já está em uso.');
        }

        $id = Uuid::uuid4();
        $tokenIntegracao = Uuid::fromString($input->getTokenIntegracao());
        $licenca = new Licenca($id, $input->getLicenca(), $tokenIntegracao);

        $this->licencaRepository->save($licenca);

        return new RegisterEmpresaResult(
            $id->toString(),
            $licenca->getLicenca(),
            $licenca->getTokenIntegracao()->toString()
        );
    }
}
