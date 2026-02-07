<?php
declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Security;

use App\Infrastructure\Security\EmpresaApiKeySubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class EmpresaApiKeySubscriberTest extends TestCase
{
    public function testReturns401WhenApiKeyIsMissing(): void
    {
        $subscriber = new EmpresaApiKeySubscriber('secret-key');
        $request    = Request::create('/empresa', 'POST');
        $event     = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->onKernelRequest($event);

        $this->assertNotNull($event->getResponse());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $event->getResponse()->getStatusCode());
    }

    public function testReturns401WhenApiKeyIsWrong(): void
    {
        $subscriber = new EmpresaApiKeySubscriber('secret-key');
        $request    = Request::create('/empresa', 'POST');
        $request->headers->set('X-Api-Key', 'wrong-key');
        $event = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->onKernelRequest($event);

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $event->getResponse()->getStatusCode());
    }

    public function testDoesNotSetResponseWhenXApiKeyMatches(): void
    {
        $subscriber = new EmpresaApiKeySubscriber('secret-key');
        $request    = Request::create('/empresa', 'POST');
        $request->headers->set('X-Api-Key', 'secret-key');
        $event = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testDoesNotSetResponseWhenBearerTokenMatches(): void
    {
        $subscriber = new EmpresaApiKeySubscriber('secret-key');
        $request    = Request::create('/empresa', 'POST');
        $request->headers->set('Authorization', 'Bearer secret-key');
        $event = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testIgnoresNonEmpresaPath(): void
    {
        $subscriber = new EmpresaApiKeySubscriber('secret-key');
        $request    = Request::create('/integrador', 'POST');
        $event     = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testReturns401WhenApiKeyNotConfiguredAndRequestHasNoKey(): void
    {
        $subscriber = new EmpresaApiKeySubscriber('');
        $request    = Request::create('/empresa', 'POST');
        $event     = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->onKernelRequest($event);

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $event->getResponse()->getStatusCode());
    }

    public function testSubscribedEvents(): void
    {
        $this->assertArrayHasKey(KernelEvents::REQUEST, EmpresaApiKeySubscriber::getSubscribedEvents());
    }
}
