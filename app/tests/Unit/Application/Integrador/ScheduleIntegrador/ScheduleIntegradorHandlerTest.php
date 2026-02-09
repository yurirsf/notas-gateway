<?php
declare(strict_types=1);

namespace App\Tests\Unit\Application\Integrador\ScheduleIntegrador;

use App\Application\Integrador\EventoFiscalMessage;
use App\Application\Integrador\ScheduleIntegrador\IntegradorPayloadInput;
use App\Application\Integrador\ScheduleIntegrador\ScheduleIntegradorHandler;
use App\Domain\Entity\Licenca;
use App\Domain\Repository\LicencaRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ScheduleIntegradorHandlerTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy<MessageBusInterface> */
    private ObjectProphecy $messageBus;

    /** @var ObjectProphecy<LicencaRepositoryInterface> */
    private ObjectProphecy $licencaRepository;

    private ScheduleIntegradorHandler $handler;

    protected function setUp(): void
    {
        $this->messageBus        = $this->prophesize(MessageBusInterface::class);
        $this->licencaRepository = $this->prophesize(LicencaRepositoryInterface::class);
        $this->handler           = new ScheduleIntegradorHandler(
            $this->messageBus->reveal(),
            $this->licencaRepository->reveal()
        );
    }

    public function testHandleReturnsWithoutDispatchWhenLicencaNotRegistered(): void
    {
        $input = new IntegradorPayloadInput(
            tipo: 'nfe',
            empresaId: '550e8400-e29b-41d4-a716-446655440000',
            nfeIdExterno: '123'
        );

        $this->licencaRepository->findByTokenIntegracao('550e8400-e29b-41d4-a716-446655440000')->willReturn(null);
        $this->messageBus->dispatch(\Prophecy\Argument::any())->shouldNotBeCalled();

        $this->handler->handle($input);
    }

    public function testHandleDispatchesEventoFiscalMessageWhenLicencaRegistered(): void
    {
        $input   = new IntegradorPayloadInput(
            tipo: 'nfe',
            empresaId: '550e8400-e29b-41d4-a716-446655440000',
            nfeIdExterno: '123'
        );
        $licenca = new Licenca(
            \Ramsey\Uuid\Uuid::uuid4(),
            'empresa123',
            \Ramsey\Uuid\Uuid::fromString('550e8400-e29b-41d4-a716-446655440000')
        );

        $this->licencaRepository->findByTokenIntegracao('550e8400-e29b-41d4-a716-446655440000')->willReturn($licenca);
        $this->messageBus->dispatch(\Prophecy\Argument::that(static function ($arg) {
            return $arg instanceof EventoFiscalMessage
                && $arg->getTipo() === 'nfe'
                && $arg->getEmpresaId() === '550e8400-e29b-41d4-a716-446655440000'
                && $arg->getNfeIdExterno() === '123';
        }))->willReturn(new Envelope(new \stdClass()))->shouldBeCalledOnce();

        $this->handler->handle($input);
    }
}
