<?php
declare(strict_types=1);

namespace App\Tests\Unit\Application\Integrador\ScheduleIntegrador;

use App\Application\Integrador\EventoFiscalMessage;
use App\Application\Integrador\ScheduleIntegrador\IntegradorPayloadInput;
use App\Application\Integrador\ScheduleIntegrador\ScheduleIntegradorHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ScheduleIntegradorHandlerTest extends TestCase
{
    use ProphecyTrait;

    private MessageBusInterface|ObjectProphecy $messageBus;

    private ScheduleIntegradorHandler $handler;

    protected function setUp(): void
    {
        $this->messageBus = $this->prophesize(MessageBusInterface::class);
        $this->handler    = new ScheduleIntegradorHandler($this->messageBus->reveal());
    }

    public function testHandleDispatchesEventoFiscalMessage(): void
    {
        $input = new IntegradorPayloadInput(
            tipo: 'nfe',
            empresaId: '550e8400-e29b-41d4-a716-446655440000',
            nfeIdExterno: '123'
        );

        $this->messageBus->dispatch(\Prophecy\Argument::that(static function ($arg) {
            return $arg instanceof EventoFiscalMessage
                && $arg->getTipo() === 'nfe'
                && $arg->getEmpresaId() === '550e8400-e29b-41d4-a716-446655440000'
                && $arg->getNfeIdExterno() === '123';
        }))->willReturn(new Envelope(new \stdClass()))->shouldBeCalledOnce();

        $this->handler->handle($input);
    }
}
