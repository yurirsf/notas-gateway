<?php
declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Application\Http\CallbackHttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

final class CallbackHttpClient implements CallbackHttpClientInterface
{
    private readonly Client $httpClient;

    private readonly LoggerInterface $logger;

    public function __construct(Client $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    /**
     * @param string               $url     ULR a ser usada
     * @param array<string, mixed> $payload Payload a ser enviado
     */
    public function sendPost(string $url, array $payload): void
    {
        try {
            $this->httpClient->request('POST', $url, [
                'json' => $payload,
                'timeout' => 30,
            ]);
        } catch (GuzzleException $e) {
            $this->logger->error('Requisição de callback falhou', [
                'message' => $e->getMessage(),
                'url' => $url,
            ]);
            throw $e;
        }
    }
}
