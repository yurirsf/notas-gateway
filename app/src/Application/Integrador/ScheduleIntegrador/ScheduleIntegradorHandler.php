<?php
declare(strict_types=1);

namespace App\Application\Integrador\ScheduleIntegrador;

use App\Application\Integrador\EventoFiscalMessage;
use App\Domain\Repository\LicencaRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ScheduleIntegradorHandler
{
    private readonly MessageBusInterface $messageBus;

    private readonly LicencaRepositoryInterface $licencaRepository;

    public function __construct(MessageBusInterface $messageBus, LicencaRepositoryInterface $licencaRepository)
    {
        $this->messageBus = $messageBus;
        $this->licencaRepository = $licencaRepository;
    }

    public function handle(IntegradorPayloadInput $input): void
    {
        $licenca = $this->licencaRepository->findByTokenIntegracao($input->getEmpresaId());

        if ($licenca === null) {
            return;
        }

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
