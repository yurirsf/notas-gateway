<?php
declare(strict_types=1);

namespace App\Application\Empresa\RegisterEmpresa;

final class RegisterEmpresaResult
{
    public function __construct(
        public string $id,
        public string $licenca,
        public string $tokenIntegracao
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'licenca' => $this->licenca,
            'token_integracao' => $this->tokenIntegracao,
        ];

        return $data;
    }
}
