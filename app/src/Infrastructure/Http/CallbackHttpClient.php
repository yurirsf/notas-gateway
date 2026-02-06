<?php
declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Application\Http\CallbackHttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

final class CallbackHttpClient implements CallbackHttpClientInterface
{
    public function __construct(
        private readonly Client $httpClient,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function sendPost(string $url, array $payload): void
    {
        try {
            $this->httpClient->request('POST', $url, [
                'json' => $payload,
                'timeout' => 30,
            ]);
        } catch (GuzzleException $e) {
            $this->logger->error('Requisição de callback falhou', [
                'url' => $url,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
