<?php
declare(strict_types=1);

namespace App\Infrastructure\Messenger;

use App\Application\Http\CallbackHttpClientInterface;
use App\Application\Integrador\EventoFiscalMessage;
use App\Domain\Repository\LicencaRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProcessIntegradorHandler
{
    private const CALLBACK_ENDPOINT = '/endpoint';

    private readonly LicencaRepositoryInterface $licencaRepository;

    private readonly CallbackHttpClientInterface $callbackHttpClient;

    private readonly string $callbackBaseUrl;

    private readonly LoggerInterface $logger;

    public function __construct(
        LicencaRepositoryInterface $licencaRepository,
        CallbackHttpClientInterface $callbackHttpClient,
        string $callbackBaseUrl,
        LoggerInterface $logger
    ) {
        $this->licencaRepository = $licencaRepository;
        $this->callbackHttpClient = $callbackHttpClient;
        $this->callbackBaseUrl = $callbackBaseUrl;
        $this->logger = $logger;
    }

    public function __invoke(EventoFiscalMessage $message): void
    {
        $this->logger->info('Processando evento do integrador.', [
            'empresaId' => $message->getEmpresaId(),
            'tipo' => $message->getTipo(),
        ]);

        $licenca = $this->licencaRepository->findByTokenIntegracao($message->getEmpresaId());

        if ($licenca === null) {
            $this->logger->warning('Licença não encontrada para o token_integracao informado.', [
                'empresaId' => $message->getEmpresaId(),
            ]);

            return;
        }

        $url = \str_replace('{licenca}', $licenca->getLicenca(), $this->callbackBaseUrl) . self::CALLBACK_ENDPOINT;
        $this->callbackHttpClient->sendPost($url, $message->toArray());

        $this->logger->info('Callback entregue.', [
            'empresaId' => $message->getEmpresaId(),
            'url' => $url,
        ]);
    }
}
