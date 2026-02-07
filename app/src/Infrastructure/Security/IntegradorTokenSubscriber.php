<?php
declare(strict_types=1);

namespace App\Infrastructure\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class IntegradorTokenSubscriber implements EventSubscriberInterface
{
    private const INTEGRADOR_PATH = '/integrador';

    private readonly string $integradorToken;

    public function __construct(string $integradorToken)
    {
        $this->integradorToken = $integradorToken;
    }

    /**
     * @return array<string, array<int, string|int>>
     */
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

        if ($request->getPathInfo() !== self::INTEGRADOR_PATH || $request->getMethod() !== 'POST') {
            return;
        }

        $provided = $this->extractToken($request);

        if ($this->integradorToken !== '' && $provided !== '' && \hash_equals($this->integradorToken, $provided)) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'message' => 'Unauthorized',
            'status' => Response::HTTP_UNAUTHORIZED,
        ], Response::HTTP_UNAUTHORIZED));
    }

    private function extractToken(\Symfony\Component\HttpFoundation\Request $request): string
    {
        $auth = $request->headers->get('Authorization', '');
        if (\str_starts_with($auth, 'Bearer ')) {
            return \trim(\substr($auth, 7));
        }

        return '';
    }
}
