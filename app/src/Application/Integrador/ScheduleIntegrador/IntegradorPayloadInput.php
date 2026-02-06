<?php
declare(strict_types=1);

namespace App\Application\Integrador\ScheduleIntegrador;

use Symfony\Component\Validator\Constraints as Assert;

final class IntegradorPayloadInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'tipo é obrigatório')]
        public string $tipo = '',

        #[Assert\NotBlank(message: 'empresaId é obrigatório')]
        public string $empresaId = '',
        
        #[Assert\NotBlank(message: 'nfeIdExterno é obrigatório')]
        public ?string $nfeIdExterno = null,

        public ?string $nfeId = null,        
        public ?string $nfeStatus = null,
        public ?string $nfeMotivoStatus = null,
        public ?string $nfeLinkPdf = null,
        public ?string $nfeLinkXml = null,
        public ?string $nfeNumero = null,
        public ?string $nfeCodigoVerificacao = null,
        public ?string $nfeNumeroRps = null,
        public ?string $nfeSerieRps = null,
        public ?string $nfeDataCompetencia = null,
    ) {
    }
}
