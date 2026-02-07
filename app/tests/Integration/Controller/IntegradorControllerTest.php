<?php
declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class IntegradorControllerTest extends KernelTestCase
{
    private HttpKernelInterface $httpKernel;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->httpKernel = $container->get('http_kernel');

        $entityManager = $container->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($entityManager->getMetadataFactory()->getAllMetadata());
    }

    public function testPostIntegradorWithoutTokenReturns401(): void
    {
        $request = Request::create(
            '/integrador',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode(['tipo' => 'nfe', 'empresaId' => '550e8400-e29b-41d4-a716-446655440000']),
        );

        $response = $this->httpKernel->handle($request, HttpKernelInterface::MAIN_REQUEST, false);

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testPostIntegradorWithInvalidPayloadReturns400(): void
    {
        $request = Request::create(
            '/integrador',
            'POST',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer integrador-token',
            ],
            \json_encode(['tipo' => '']),
        );

        $response = $this->httpKernel->handle($request, HttpKernelInterface::MAIN_REQUEST, false);

        $this->assertSame(400, $response->getStatusCode());
        $content = \json_decode((string) $response->getContent(), true);
        $this->assertArrayHasKey('errors', $content);
    }

    public function testPostIntegradorWithValidPayloadReturns202(): void
    {
        $request = Request::create(
            '/integrador',
            'POST',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer integrador-token',
            ],
            \json_encode([
                'empresaId' => '550e8400-e29b-41d4-a716-446655440000',
                'nfeIdExterno' => '123',
                'tipo' => 'nfe',
            ]),
        );

        $response = $this->httpKernel->handle($request, HttpKernelInterface::MAIN_REQUEST, false);

        $this->assertSame(202, $response->getStatusCode());
        $content = \json_decode((string) $response->getContent(), true);
        $this->assertSame('Accepted', $content['message']);
    }
}
