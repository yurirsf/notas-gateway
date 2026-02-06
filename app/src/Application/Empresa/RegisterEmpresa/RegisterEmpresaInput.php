<?php
declare(strict_types=1);

namespace App\Application\Empresa\RegisterEmpresa;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterEmpresaInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'licenca é obrigatório')]
        #[Assert\Type('string')]
        #[Assert\Length(max: 255)]
        public string $licenca = '',

        #[Assert\NotBlank(message: 'token_integracao é obrigatório')]
        #[Assert\Type('string')]
        #[Assert\Uuid(message: 'token_integracao deve ser um UUID válido')]
        public string $tokenIntegracao = '',
    ) {
    }
}
