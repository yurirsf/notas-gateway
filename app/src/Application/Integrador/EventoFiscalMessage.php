<?php
declare(strict_types=1);

namespace App\Application\Integrador;

final class EventoFiscalMessage
{
    private string $tipo;

    private string $empresaId;

    private ?string $nfeIdExterno;

    private ?string $nfeId;

    private ?string $nfeStatus;

    private ?string $nfeMotivoStatus;

    private ?string $nfeLinkPdf;

    private ?string $nfeLinkXml;

    private ?string $nfeNumero;

    private ?string $nfeCodigoVerificacao;

    private ?string $nfeNumeroRps;

    private ?string $nfeSerieRps;

    private ?string $nfeDataCompetencia;

    public function __construct(
        string $tipo,
        string $empresaId,
        ?string $nfeIdExterno = null,
        ?string $nfeId = null,
        ?string $nfeStatus = null,
        ?string $nfeMotivoStatus = null,
        ?string $nfeLinkPdf = null,
        ?string $nfeLinkXml = null,
        ?string $nfeNumero = null,
        ?string $nfeCodigoVerificacao = null,
        ?string $nfeNumeroRps = null,
        ?string $nfeSerieRps = null,
        ?string $nfeDataCompetencia = null
    ) {
        $this->tipo = $tipo;
        $this->empresaId = $empresaId;
        $this->nfeIdExterno = $nfeIdExterno;
        $this->nfeId = $nfeId;
        $this->nfeStatus = $nfeStatus;
        $this->nfeMotivoStatus = $nfeMotivoStatus;
        $this->nfeLinkPdf = $nfeLinkPdf;
        $this->nfeLinkXml = $nfeLinkXml;
        $this->nfeNumero = $nfeNumero;
        $this->nfeCodigoVerificacao = $nfeCodigoVerificacao;
        $this->nfeNumeroRps = $nfeNumeroRps;
        $this->nfeSerieRps = $nfeSerieRps;
        $this->nfeDataCompetencia = $nfeDataCompetencia;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function getEmpresaId(): string
    {
        return $this->empresaId;
    }

    public function getNfeIdExterno(): ?string
    {
        return $this->nfeIdExterno;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return \array_filter([
            'empresaId' => $this->empresaId,
            'nfeCodigoVerificacao' => $this->nfeCodigoVerificacao,
            'nfeDataCompetencia' => $this->nfeDataCompetencia,
            'nfeId' => $this->nfeId,
            'nfeIdExterno' => $this->nfeIdExterno,
            'nfeLinkPdf' => $this->nfeLinkPdf,
            'nfeLinkXml' => $this->nfeLinkXml,
            'nfeMotivoStatus' => $this->nfeMotivoStatus,
            'nfeNumero' => $this->nfeNumero,
            'nfeNumeroRps' => $this->nfeNumeroRps,
            'nfeSerieRps' => $this->nfeSerieRps,
            'nfeStatus' => $this->nfeStatus,
            'tipo' => $this->tipo,
        ], static function ($v): bool {
            return $v !== null;
        });
    }
}
