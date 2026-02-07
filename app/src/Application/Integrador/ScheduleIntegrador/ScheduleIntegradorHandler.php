<?php
declare(strict_types=1);

namespace App\Application\Integrador\ScheduleIntegrador;

use App\Application\Integrador\EventoFiscalMessage;
use Symfony\Component\Messenger\MessageBusInterface;

final class ScheduleIntegradorHandler
{
    private readonly MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function handle(IntegradorPayloadInput $input): void
    {
        $message = new EventoFiscalMessage(
            $input->getTipo(),
            $input->getEmpresaId(),
            $input->getNfeIdExterno(),
            $input->getNfeId(),
            $input->getNfeStatus(),
            $input->getNfeMotivoStatus(),
            $input->getNfeLinkPdf(),
            $input->getNfeLinkXml(),
            $input->getNfeNumero(),
            $input->getNfeCodigoVerificacao(),
            $input->getNfeNumeroRps(),
            $input->getNfeSerieRps(),
            $input->getNfeDataCompetencia()
        );

        $this->messageBus->dispatch($message);
    }
}
