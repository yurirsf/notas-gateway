<?php
declare(strict_types=1);

namespace App\Tests\Unit\Application\Empresa\RegisterEmpresa;

use App\Application\Empresa\RegisterEmpresa\LicencaAlreadyRegisteredException;
use App\Application\Empresa\RegisterEmpresa\RegisterEmpresaHandler;
use App\Application\Empresa\RegisterEmpresa\RegisterEmpresaInput;
use App\Application\Empresa\RegisterEmpresa\TokenIntegracaoAlreadyUsedException;
use App\Domain\Entity\Licenca;
use App\Domain\Repository\LicencaRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid;

class RegisterEmpresaHandlerTest extends TestCase
{
    use ProphecyTrait;

    private LicencaRepositoryInterface|ObjectProphecy $repository;

    private RegisterEmpresaHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(LicencaRepositoryInterface::class);
        $this->handler    = new RegisterEmpresaHandler($this->repository->reveal());
    }

    public function testHandleRegistersNewLicenca(): void
    {
        $input = new RegisterEmpresaInput('empresa123', Uuid::uuid4()->toString());

        $this->repository->findByLicenca('empresa123')->willReturn(null);
        $this->repository->findByTokenIntegracao($input->getTokenIntegracao())->willReturn(null);
        $this->repository->save(\Prophecy\Argument::type(Licenca::class))->shouldBeCalledOnce();

        $result = $this->handler->handle($input);

        $this->assertNotEmpty($result->getId());
        $this->assertSame('empresa123', $result->getLicenca());
        $this->assertSame($input->getTokenIntegracao(), $result->getTokenIntegracao());
        $this->assertSame([
            'id' => $result->getId(),
            'licenca' => 'empresa123',
            'token_integracao' => $input->getTokenIntegracao(),
        ], $result->toArray());
    }

    public function testHandleThrowsWhenLicencaAlreadyExists(): void
    {
        $input   = new RegisterEmpresaInput('empresa123', Uuid::uuid4()->toString());
        $existing = new Licenca(Uuid::uuid4(), 'empresa123', Uuid::uuid4());

        $this->repository->findByLicenca('empresa123')->willReturn($existing);
        $this->repository->save(\Prophecy\Argument::any())->shouldNotBeCalled();

        $this->expectException(LicencaAlreadyRegisteredException::class);
        $this->expectExceptionMessage('Já existe uma licença registrada');

        $this->handler->handle($input);
    }

    public function testHandleThrowsWhenTokenIntegracaoAlreadyUsed(): void
    {
        $token   = Uuid::uuid4()->toString();
        $input   = new RegisterEmpresaInput('outra-empresa', $token);
        $existing = new Licenca(Uuid::uuid4(), 'outra', Uuid::fromString($token));

        $this->repository->findByLicenca('outra-empresa')->willReturn(null);
        $this->repository->findByTokenIntegracao($token)->willReturn($existing);
        $this->repository->save(\Prophecy\Argument::any())->shouldNotBeCalled();

        $this->expectException(TokenIntegracaoAlreadyUsedException::class);
        $this->expectExceptionMessage('token_integracao');

        $this->handler->handle($input);
    }
}
