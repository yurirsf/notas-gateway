<?php
declare(strict_types=1);

namespace App\Application\Empresa\RegisterEmpresa;

final class RegisterEmpresaResult
{
    private string $id;

    private string $licenca;

    private string $tokenIntegracao;

    public function __construct(string $id, string $licenca, string $tokenIntegracao)
    {
        $this->id = $id;
        $this->licenca = $licenca;
        $this->tokenIntegracao = $tokenIntegracao;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLicenca(): string
    {
        return $this->licenca;
    }

    public function getTokenIntegracao(): string
    {
        return $this->tokenIntegracao;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'licenca' => $this->licenca,
            'token_integracao' => $this->tokenIntegracao,
        ];
    }
}
