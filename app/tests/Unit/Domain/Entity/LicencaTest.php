<?php
declare(strict_types=1);

namespace App\Tests\Unit\Domain\Entity;

use App\Domain\Entity\Licenca;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class LicencaTest extends TestCase
{
    public function testCreateLicenca(): void
    {
        $id             = Uuid::uuid4();
        $tokenIntegracao = Uuid::uuid4();
        $licenca        = new Licenca($id, 'empresa123', $tokenIntegracao);

        $this->assertSame($id, $licenca->getId());
        $this->assertSame('empresa123', $licenca->getLicenca());
        $this->assertSame($tokenIntegracao, $licenca->getTokenIntegracao());
    }

    public function testTouchUpdatesUpdatedAt(): void
    {
        $licenca = new Licenca(Uuid::uuid4(), 'empresa123', Uuid::uuid4());
        $before = $licenca->getUpdatedAt();

        \sleep(1);
        $licenca->touch();

        $this->assertNotEquals($before->getTimestamp(), $licenca->getUpdatedAt()->getTimestamp());
    }
}
