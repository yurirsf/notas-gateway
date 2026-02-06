<?php
declare(strict_types=1);

namespace App\Infrastructure\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class EmpresaApiKeySubscriber implements EventSubscriberInterface
{
    private const EMPRESA_PATH = '/empresa';

    public function __construct(
        private readonly string $apiKey,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 32],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if ($request->getPathInfo() !== self::EMPRESA_PATH || $request->getMethod() !== 'POST') {
            return;
        }

        $provided = $this->extractApiKey($request);

        if ($this->apiKey !== '' && $provided !== '' && \hash_equals($this->apiKey, $provided)) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'message' => 'Unauthorized',
            'status' => Response::HTTP_UNAUTHORIZED,
        ], Response::HTTP_UNAUTHORIZED));
    }

    private function extractApiKey(\Symfony\Component\HttpFoundation\Request $request): string
    {
        if ($request->headers->has('X-Api-Key')) {
            return (string) $request->headers->get('X-Api-Key');
        }

        $auth = $request->headers->get('Authorization', '');

        if (str_starts_with($auth, 'Bearer ')) {
            return trim(substr($auth, 7));
        }

        return '';
    }
}
