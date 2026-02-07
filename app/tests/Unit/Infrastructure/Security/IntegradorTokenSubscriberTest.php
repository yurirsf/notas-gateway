<?php
declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Security;

use App\Infrastructure\Security\IntegradorTokenSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class IntegradorTokenSubscriberTest extends TestCase
{
    public function testReturns401WhenTokenIsMissing(): void
    {
        $subscriber = new IntegradorTokenSubscriber('fixed-token');
        $request    = Request::create('/integrador', 'POST');
        $event     = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->onKernelRequest($event);

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $event->getResponse()->getStatusCode());
    }

    public function testReturns401WhenTokenIsWrong(): void
    {
        $subscriber = new IntegradorTokenSubscriber('fixed-token');
        $request    = Request::create('/integrador', 'POST');
        $request->headers->set('Authorization', 'Bearer wrong-token');
        $event = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->onKernelRequest($event);

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $event->getResponse()->getStatusCode());
    }

    public function testDoesNotSetResponseWhenTokenMatches(): void
    {
        $subscriber = new IntegradorTokenSubscriber('fixed-token');
        $request    = Request::create('/integrador', 'POST');
        $request->headers->set('Authorization', 'Bearer fixed-token');
        $event = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testIgnoresNonIntegradorPath(): void
    {
        $subscriber = new IntegradorTokenSubscriber('fixed-token');
        $request    = Request::create('/empresa', 'POST');
        $event     = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testSubscribedEvents(): void
    {
        $this->assertArrayHasKey(KernelEvents::REQUEST, IntegradorTokenSubscriber::getSubscribedEvents());
    }
}
