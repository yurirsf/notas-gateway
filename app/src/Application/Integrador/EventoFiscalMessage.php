<?php
declare(strict_types=1);

namespace App\Application\Integrador;

final class EventoFiscalMessage
{
    public function __construct(
        public readonly string $tipo,
        public readonly string $empresaId,
        public readonly ?string $nfeIdExterno,
        public readonly ?string $nfeId = null,
        public readonly ?string $nfeStatus = null,
        public readonly ?string $nfeMotivoStatus = null,
        public readonly ?string $nfeLinkPdf = null,
        public readonly ?string $nfeLinkXml = null,
        public readonly ?string $nfeNumero = null,
        public readonly ?string $nfeCodigoVerificacao = null,
        public readonly ?string $nfeNumeroRps = null,
        public readonly ?string $nfeSerieRps = null,
        public readonly ?string $nfeDataCompetencia = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'tipo' => $this->tipo,
            'empresaId' => $this->empresaId,
            'nfeId' => $this->nfeId,
            'nfeIdExterno' => $this->nfeIdExterno,
            'nfeStatus' => $this->nfeStatus,
            'nfeMotivoStatus' => $this->nfeMotivoStatus,
            'nfeLinkPdf' => $this->nfeLinkPdf,
            'nfeLinkXml' => $this->nfeLinkXml,
            'nfeNumero' => $this->nfeNumero,
            'nfeCodigoVerificacao' => $this->nfeCodigoVerificacao,
            'nfeNumeroRps' => $this->nfeNumeroRps,
            'nfeSerieRps' => $this->nfeSerieRps,
            'nfeDataCompetencia' => $this->nfeDataCompetencia,
        ], static fn ($v): bool => $v !== null);
    }
}
