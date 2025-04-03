<?php

declare(strict_types=1);

namespace Platine\Test\Event;

use Platine\Dev\PlatineTestCase;
use Platine\Event\Event;
use Platine\Event\Listener\CallableListener;
use Platine\Event\Listener\ListenerInterface;

/**
 * CallableListener class tests
 *
 * @group core
 * @group event
 */
class CallableListenerTest extends PlatineTestCase
{
    public function testConstructor(): void
    {
        $callable = 'Platine\\Test\\Fixture\\callable_listener';
        $cl = new CallableListener($callable);
        $this->assertInstanceOf(CallableListener::class, $cl);
    }

    public function testGetCallable(): void
    {
        $callable = 'Platine\\Test\\Fixture\\callable_listener';
        $cl = new CallableListener($callable);
        $this->assertEquals($callable, $cl->getCallable());
    }

    public function testCreateFromCallable(): void
    {
        $callable = 'Platine\\Test\\Fixture\\callable_listener';
        $cl = CallableListener::fromCallable($callable);
        $this->assertInstanceOf(CallableListener::class, $cl);
    }

    public function testGetListener(): void
    {
        $callable = 'Platine\\Test\\Fixture\\callable_listener';
        $cl = new CallableListener($callable);
        $this->assertEquals($cl, CallableListener::getListener($callable));
        $this->assertFalse(CallableListener::getListener('strlen'));
    }

    public function testClear(): void
    {
        $callable = 'Platine\\Test\\Fixture\\callable_listener';
        $cl = new CallableListener($callable);
        $this->assertInstanceOf(ListenerInterface::class, CallableListener::getListener($callable));
        CallableListener::clear();
        $this->assertFalse(CallableListener::getListener($callable));
    }

    public function testHandle(): void
    {
        $callable = 'Platine\\Test\\Fixture\\callable_listener';
        $cl = new CallableListener($callable);
        $this->expectOutputString('foo_event');
        $cl->handle(new Event('foo_event'));
    }
}
