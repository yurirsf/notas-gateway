<?php
declare(strict_types=1);

namespace App\Tests\Unit\Application\Integrador;

use App\Application\Integrador\EventoFiscalMessage;
use PHPUnit\Framework\TestCase;

class EventoFiscalMessageTest extends TestCase
{
    public function testToArrayIncludesRequiredFields(): void
    {
        $message = new EventoFiscalMessage('nfe', '550e8400-e29b-41d4-a716-446655440000');

        $data = $message->toArray();

        $this->assertSame('nfe', $data['tipo']);
        $this->assertSame('550e8400-e29b-41d4-a716-446655440000', $data['empresaId']);
    }

    public function testToArrayIncludesOptionalFieldsWhenSet(): void
    {
        $message = new EventoFiscalMessage(
            'nfe',
            '550e8400-e29b-41d4-a716-446655440000',
            nfeIdExterno: '123',
            nfeLinkPdf: 'https://example.com/pdf'
        );

        $data = $message->toArray();

        $this->assertSame('123', $data['nfeIdExterno']);
        $this->assertSame('https://example.com/pdf', $data['nfeLinkPdf']);
    }

    public function testToArrayOmitsNullOptionalFields(): void
    {
        $message = new EventoFiscalMessage('nfe', '550e8400-e29b-41d4-a716-446655440000');

        $data = $message->toArray();

        $this->assertArrayNotHasKey('nfeId', $data);
        $this->assertArrayNotHasKey('nfeLinkPdf', $data);
    }
}
