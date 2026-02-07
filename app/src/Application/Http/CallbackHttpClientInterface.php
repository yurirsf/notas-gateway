<?php
declare(strict_types=1);

namespace App\Application\Http;

interface CallbackHttpClientInterface
{
    /**
     * @param string               $url     ULR a ser usada
     * @param array<string, mixed> $payload Payload a ser enviado
     */
    public function sendPost(string $url, array $payload): void;
}
