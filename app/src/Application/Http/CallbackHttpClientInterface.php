<?php
declare(strict_types=1);

namespace App\Application\Http;

interface CallbackHttpClientInterface
{
    public function sendPost(string $url, array $payload): void;
}
