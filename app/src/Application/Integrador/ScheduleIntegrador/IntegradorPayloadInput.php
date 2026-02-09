<?php
declare(strict_types=1);

namespace App\Application\Integrador\ScheduleIntegrador;

use Symfony\Component\Validator\Constraints as Assert;

final class IntegradorPayloadInput
{
    #[Assert\NotBlank(message: 'tipo é obrigatório')]
    #[Assert\Type('string')]
    private string $tipo = '';

    #[Assert\NotBlank(message: 'empresaId é obrigatório')]
    #[Assert\Type('string')]
    private string $empresaId = '';

    #[Assert\NotBlank(message: 'nfeIdExterno é obrigatório')]
    private ?string $nfeIdExterno = null;

    private ?string $nfeId = null;

    private ?string $nfeStatus = null;

    private ?string $nfeMotivoStatus = null;

    private ?string $nfeLinkPdf = null;

    private ?string $nfeLinkXml = null;

    private ?string $nfeNumero = null;

    private ?string $nfeCodigoVerificacao = null;

    private ?string $nfeNumeroRps = null;

    private ?string $nfeSerieRps = null;

    private ?string $nfeDataCompetencia = null;

    public function __construct(
        string $tipo = '',
        string $empresaId = '',
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

    public function getNfeId(): ?string
    {
        return $this->nfeId;
    }

    public function getNfeStatus(): ?string
    {
        return $this->nfeStatus;
    }

    public function getNfeMotivoStatus(): ?string
    {
        return $this->nfeMotivoStatus;
    }

    public function getNfeLinkPdf(): ?string
    {
        return $this->nfeLinkPdf;
    }

    public function getNfeLinkXml(): ?string
    {
        return $this->nfeLinkXml;
    }

    public function getNfeNumero(): ?string
    {
        return $this->nfeNumero;
    }

    public function getNfeCodigoVerificacao(): ?string
    {
        return $this->nfeCodigoVerificacao;
    }

    public function getNfeNumeroRps(): ?string
    {
        return $this->nfeNumeroRps;
    }

    public function getNfeSerieRps(): ?string
    {
        return $this->nfeSerieRps;
    }

    public function getNfeDataCompetencia(): ?string
    {
        return $this->nfeDataCompetencia;
    }
}
