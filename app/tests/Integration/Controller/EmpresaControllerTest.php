<?php
declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EmpresaControllerTest extends KernelTestCase
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

    public function testPostEmpresaWithoutApiKeyReturns401(): void
    {
        $request = Request::create(
            '/empresa',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode(['licenca' => 'test', 'token_integracao' => '550e8400-e29b-41d4-a716-446655440000']),
        );

        $response = $this->httpKernel->handle($request, HttpKernelInterface::MAIN_REQUEST, false);

        $this->assertSame(401, $response->getStatusCode());
        $this->assertStringContainsString('Unauthorized', (string) $response->getContent());
    }

    public function testPostEmpresaWithInvalidPayloadReturns400(): void
    {
        $request = Request::create(
            '/empresa',
            'POST',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_API_KEY' => 'test-key',
            ],
            \json_encode(['licenca' => '']),
        );

        $response = $this->httpKernel->handle($request, HttpKernelInterface::MAIN_REQUEST, false);

        $this->assertSame(400, $response->getStatusCode());
        $content = \json_decode((string) $response->getContent(), true);
        $this->assertArrayHasKey('errors', $content);
    }

    public function testPostEmpresaWithValidPayloadReturns201(): void
    {
        $token = '550e8400-e29b-41d4-a716-446655440001';
        $request = Request::create(
            '/empresa',
            'POST',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_API_KEY' => 'test-key',
            ],
            \json_encode(['licenca' => 'empresa-integration-test', 'token_integracao' => $token]),
        );

        $response = $this->httpKernel->handle($request, HttpKernelInterface::MAIN_REQUEST, false);

        $this->assertSame(201, $response->getStatusCode());
        $content = \json_decode((string) $response->getContent(), true);
        $this->assertArrayHasKey('id', $content);
        $this->assertSame('empresa-integration-test', $content['licenca']);
        $this->assertSame($token, $content['token_integracao']);
    }
}
