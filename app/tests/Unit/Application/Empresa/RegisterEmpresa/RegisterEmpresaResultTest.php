<?php
declare(strict_types=1);

namespace App\Tests\Unit\Application\Empresa\RegisterEmpresa;

use App\Application\Empresa\RegisterEmpresa\RegisterEmpresaResult;
use PHPUnit\Framework\TestCase;

class RegisterEmpresaResultTest extends TestCase
{
    public function testToArrayIncludesLicencaAndTokenIntegracao(): void
    {
        $result = new RegisterEmpresaResult(
            id: '550e8400-e29b-41d4-a716-446655440000',
            licenca: 'empresa123',
            tokenIntegracao: '660e8400-e29b-41d4-a716-446655440000'
        );

        $data = $result->toArray();

        $this->assertArrayHasKey('licenca', $data);
        $this->assertSame('empresa123', $data['licenca']);
        $this->assertSame('660e8400-e29b-41d4-a716-446655440000', $data['token_integracao']);
    }
}
