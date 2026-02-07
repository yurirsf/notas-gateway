<?php
declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Http;

use App\Infrastructure\Http\CallbackHttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class CallbackHttpClientTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy<Client> */
    private ObjectProphecy $guzzle;

    /** @var ObjectProphecy<LoggerInterface> */
    private ObjectProphecy $logger;

    private CallbackHttpClient $client;

    protected function setUp(): void
    {
        $this->guzzle = $this->prophesize(Client::class);
        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->client = new CallbackHttpClient($this->guzzle->reveal(), $this->logger->reveal());
    }

    public function testSendPostCallsGuzzle(): void
    {
        $this->guzzle->request('POST', 'https://example.com/endpoint', Argument::that(static function ($opts) {
            return isset($opts['json']) && $opts['json'] === ['tipo' => 'nfe']
                && isset($opts['timeout']) && $opts['timeout'] === 30;
        }))->shouldBeCalledOnce();

        $this->client->sendPost('https://example.com/endpoint', ['tipo' => 'nfe']);
    }

    public function testSendPostLogsAndRethrowsOnFailure(): void
    {
        $this->guzzle->request(\Prophecy\Argument::cetera())->willThrow(new TransferException('Connection refused'));
        $this->logger->error('Callback HTTP request failed', \Prophecy\Argument::type('array'))->shouldBeCalledOnce();

        $this->expectException(TransferException::class);
        $this->expectExceptionMessage('Connection refused');

        $this->client->sendPost('https://example.com/endpoint', []);
    }
}
