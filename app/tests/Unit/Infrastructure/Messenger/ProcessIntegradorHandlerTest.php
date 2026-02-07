<?php
declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Messenger;

use App\Application\Http\CallbackHttpClientInterface;
use App\Application\Integrador\EventoFiscalMessage;
use App\Domain\Entity\Licenca;
use App\Domain\Repository\LicencaRepositoryInterface;
use App\Infrastructure\Messenger\ProcessIntegradorHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class ProcessIntegradorHandlerTest extends TestCase
{
    use ProphecyTrait;

    private LicencaRepositoryInterface|ObjectProphecy $repository;

    private CallbackHttpClientInterface|ObjectProphecy $callbackClient;

    private LoggerInterface|ObjectProphecy $logger;

    private ProcessIntegradorHandler $handler;

    protected function setUp(): void
    {
        $this->repository    = $this->prophesize(LicencaRepositoryInterface::class);
        $this->callbackClient = $this->prophesize(CallbackHttpClientInterface::class);
        $this->logger        = $this->prophesize(LoggerInterface::class);

        $this->handler = new ProcessIntegradorHandler(
            $this->repository->reveal(),
            $this->callbackClient->reveal(),
            'https://{licenca}.superlogica.net',
            $this->logger->reveal()
        );
    }

    public function testInvokeSendsCallbackWhenLicencaFound(): void
    {
        $message = new EventoFiscalMessage('nfe', '550e8400-e29b-41d4-a716-446655440000', nfeIdExterno: '123');
        $licenca = new Licenca(Uuid::uuid4(), 'empresa123', Uuid::fromString($message->getEmpresaId()));

        $this->repository->findByTokenIntegracao($message->getEmpresaId())->willReturn($licenca);
        $this->callbackClient->sendPost(
            'https://empresa123.superlogica.net/endpoint',
            \Prophecy\Argument::type('array')
        )->shouldBeCalledOnce();

        ($this->handler)($message);
    }

    public function testInvokeSkipsCallbackWhenLicencaNotFound(): void
    {
        $message = new EventoFiscalMessage('nfe', '550e8400-e29b-41d4-a716-446655440000');

        $this->repository->findByTokenIntegracao($message->getEmpresaId())->willReturn(null);
        $this->callbackClient->sendPost(\Prophecy\Argument::any(), \Prophecy\Argument::any())->shouldNotBeCalled();

        ($this->handler)($message);
    }
}
