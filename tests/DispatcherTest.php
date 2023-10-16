<?php

declare(strict_types=1);

namespace Platine\Test\Event;

use Platine\Dev\PlatineTestCase;
use Platine\Event\CallableListener;
use Platine\Event\Dispatcher;
use Platine\Event\Event;
use Platine\Event\Exception\DispatcherException;
use Platine\Test\Fixture\EventListenerTestClass;
use Platine\Test\Fixture\EventListenerTestClassEventInstanceChanged;
use Platine\Test\Fixture\EventListenerTestClassStopPropagation;
use Platine\Test\Fixture\EventSubscriberTestClass;

/**
 * Dispatcher class tests
 *
 * @group core
 * @group event
 */
class DispatcherTest extends PlatineTestCase
{
    public function testAddListenerDefault(): void
    {
        $d = new Dispatcher();
        $listener = new EventListenerTestClass();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener);
        $this->assertEquals(1, count($d->getListeners()));
    }

    public function testAddListenerInvalidListener(): void
    {
        $this->expectException(DispatcherException::class);
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', 'invalid_listener');
    }

    public function testDispatchParamIsEventInstance(): void
    {
        $listener = new EventListenerTestClass();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener);
        $this->assertEquals(1, count($d->getListeners()));
        $d->dispatch(new Event('foo_event'));
        $this->expectOutputString('foo_event');
    }

    public function testDispatchEventInstanceIsChangedInsideListener(): void
    {
        $listener = new EventListenerTestClassEventInstanceChanged();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener);
        $this->assertEquals(1, count($d->getListeners()));
        $e = new Event('foo_event');
        $this->assertNull($e->getArgument('foo'));
        $d->dispatch($e);
        $this->assertNotEmpty($e->getArgument('foo'));
        $this->assertEquals('bar', $e->getArgument('foo'));
    }

    public function testDispatchEventIsStopPropagation(): void
    {
        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassStopPropagation();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener1);
        $d->addListener('foo_event', $listener2, 100);
        $this->assertEquals(2, count($d->getListeners()));

        $event = new Event('foo_event');
        $d->dispatch($event);
        $this->expectOutputString('');
    }

    public function testRemoveListener(): void
    {
        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassStopPropagation();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener1);
        $d->addListener('foo_event', $listener2, 100);
        $this->assertEquals(2, count($d->getListeners()));

        $d->removeListener('foo_event', $listener1);
        $this->assertEquals(1, count($d->getListeners()));
    }

    public function testRemoveListenerEventNotExist(): void
    {
        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassStopPropagation();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener1);
        $d->addListener('foo_event', $listener2, 100);
        $this->assertEquals(2, count($d->getListeners()));

        $d->removeListener('foo_event_not_exist', $listener1);
        $this->assertEquals(2, count($d->getListeners()));
    }

    public function testRemoveListenerCallableNotExist(): void
    {
        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassStopPropagation();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener1);
        $d->addListener('foo_event', $listener2, 100);
        $this->assertEquals(2, count($d->getListeners()));

        $d->removeListener('foo_event', 'strlen');
        $this->assertEquals(2, count($d->getListeners()));
    }

    public function testRemoveAllListenerEventIsNotNull(): void
    {
        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassStopPropagation();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener1);
        $d->addListener('bar_event', $listener2, 100);
        $this->assertEquals(2, count($d->getListeners()));

        $d->removeAllListener('foo_event');
        $this->assertEquals(1, count($d->getListeners()));
    }

    public function testRemoveAllListenerEventIsNull(): void
    {
        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassStopPropagation();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener1);
        $d->addListener('bar_event', $listener2, 100);
        $this->assertEquals(2, count($d->getListeners()));

        $d->removeAllListener(null);
        $this->assertEquals(0, count($d->getListeners()));
    }

    public function testGetListenerEventIsNotNull(): void
    {
        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassStopPropagation();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener1);
        $d->addListener('bar_event', $listener2, 100);
        $this->assertEquals(2, count($d->getListeners()));

        $result = $d->getListeners('foo_event');
        $this->assertEquals(1, count($result));
    }

    public function testGetAllListenerEventIsNull(): void
    {
        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassStopPropagation();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener1);
        $d->addListener('bar_event', $listener2, 100);
        $this->assertEquals(2, count($d->getListeners()));

        $result = $d->getListeners(null);
        $this->assertEquals(2, count($result));
    }

    public function testRemoveSubscriber(): void
    {
        $subscriber = new EventSubscriberTestClass();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addSubscriber($subscriber);
        $this->assertEquals(2, count($d->getListeners()));

        $d->removeSubscriber($subscriber);
        $this->assertEquals(0, count($d->getListeners()));
    }

    public function testAddSubscriber(): void
    {
        $subscriber = new EventSubscriberTestClass();
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addSubscriber($subscriber);
        $this->assertEquals(2, count($d->getListeners()));
    }

    public function testHasListener(): void
    {
        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassStopPropagation();
        $d = new Dispatcher();

        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener1);
        $d->addListener('bar_event', $listener2, 100);
        $this->assertEquals(2, count($d->getListeners()));

        $this->assertTrue($d->hasListener('foo_event', $listener1));
        $this->assertTrue($d->hasListener('bar_event', $listener2));
        $this->assertFalse($d->hasListener('foo_event', $listener2));
        $this->assertFalse($d->hasListener('bar_event', $listener1));
        $this->assertFalse($d->hasListener('not_found_event', $listener1));
    }

    public function testHasListenerForCallable(): void
    {
        CallableListener::clear();

        $d = new Dispatcher();

        $listener = 'Platine\\Test\\Fixture\\callable_listener';
        $d->addListener('foo_event', $listener);
        $this->assertTrue($d->hasListener('foo_event', $listener));
        $this->assertFalse($d->hasListener('foo_event', function (Event $e) {
        }));
    }

    public function testAddListenerUsingCallable(): void
    {
        $listener = 'Platine\\Test\\Fixture\\callable_listener';
        $d = new Dispatcher();
        $this->assertEquals(0, count($d->getListeners()));
        $d->addListener('foo_event', $listener);
        $this->assertEquals(1, count($d->getListeners()));
    }

    public function testDispatchParamIsString(): void
    {
        $listener = 'Platine\\Test\\Fixture\\callable_listener';
        $d = new Dispatcher();
        $this->assertCount(0, $d->getListeners());
        $d->addListener('foo_event', $listener);
        $this->assertEquals(1, count($d->getListeners()));
        $d->dispatch('foo_event');
        $this->expectOutputString('foo_event');
    }
}
