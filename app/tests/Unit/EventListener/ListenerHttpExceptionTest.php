<?php
declare(strict_types=1);

namespace App\Tests\Unit\EventListener;

use App\EventListener\ListenerHttpException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ListenerHttpExceptionTest extends TestCase
{
    use ProphecyTrait;

    public function testOnKernelException(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = new Request();
        $exception = new NotFoundHttpException('Ue?');

        $event = new ExceptionEvent(
            kernel: $kernel->reveal(),
            request: $request,
            requestType: 1,
            e: $exception,
        );

        $listener = new ListenerHttpException();
        $listener->onKernelException($event);

        $this->assertInstanceOf(JsonResponse::class, $event->getResponse());
    }

    public function testOnKernelExceptionSkipResponse(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = new Request();
        $exception = new \Exception('Ue?');

        $event = new ExceptionEvent(
            kernel: $kernel->reveal(),
            request: $request,
            requestType: 1,
            e: $exception,
        );

        $listener = new ListenerHttpException();
        $listener->onKernelException($event);
        $this->assertEmpty($event->getResponse());
    }
}
