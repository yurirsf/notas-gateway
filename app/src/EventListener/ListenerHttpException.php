<?php
declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

#[AsEventListener(event: 'kernel.exception', method: 'onKernelException')]
class ListenerHttpException
{
    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->getThrowable() instanceof HttpExceptionInterface) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'message' => $event->getThrowable()->getMessage(),
            'status'  => $event->getThrowable()->getStatusCode(),
        ], $event->getThrowable()->getStatusCode()));
    }
}
