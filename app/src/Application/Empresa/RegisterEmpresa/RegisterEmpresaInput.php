<?php
declare(strict_types=1);

namespace App\Application\Empresa\RegisterEmpresa;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterEmpresaInput
{
    #[Assert\NotBlank(message: 'licenca é obrigatório')]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    private string $licenca = '';

    #[Assert\NotBlank(message: 'token_integracao é obrigatório')]
    #[Assert\Type('string')]
    #[Assert\Uuid(message: 'token_integracao deve ser um UUID válido')]
    private string $tokenIntegracao = '';

    public function __construct(string $licenca = '', string $tokenIntegracao = '')
    {
        $this->licenca = $licenca;
        $this->tokenIntegracao = $tokenIntegracao;
    }

    public function getLicenca(): string
    {
        return $this->licenca;
    }

    public function getTokenIntegracao(): string
    {
        return $this->tokenIntegracao;
    }
}
