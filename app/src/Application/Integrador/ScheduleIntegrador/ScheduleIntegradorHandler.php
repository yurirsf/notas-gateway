<?php
declare(strict_types=1);

namespace App\Application\Integrador\ScheduleIntegrador;

use App\Application\Integrador\EventoFiscalMessage;
use Symfony\Component\Messenger\MessageBusInterface;

final class ScheduleIntegradorHandler
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function handle(IntegradorPayloadInput $input): void
    {
        $message = new EventoFiscalMessage(
            tipo: $input->tipo,
            empresaId: $input->empresaId,
            nfeId: $input->nfeId,
            nfeIdExterno: $input->nfeIdExterno,
            nfeStatus: $input->nfeStatus,
            nfeMotivoStatus: $input->nfeMotivoStatus,
            nfeLinkPdf: $input->nfeLinkPdf,
            nfeLinkXml: $input->nfeLinkXml,
            nfeNumero: $input->nfeNumero,
            nfeCodigoVerificacao: $input->nfeCodigoVerificacao,
            nfeNumeroRps: $input->nfeNumeroRps,
            nfeSerieRps: $input->nfeSerieRps,
            nfeDataCompetencia: $input->nfeDataCompetencia,
        );

        $this->messageBus->dispatch($message);
    }
}
