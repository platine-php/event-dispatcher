<?php

declare(strict_types=1);

namespace Platine\Test\Event;

use Platine\Event\ListenerPriorityQueue;
use Platine\Dev\PlatineTestCase;
use Platine\Test\Fixture\EventListenerTestClass;
use Platine\Test\Fixture\EventListenerTestClassEmpty;

/**
 * ListenerPriorityQueue class tests
 *
 * @group core
 * @group event
 */
class ListenerPriorityQueueTest extends PlatineTestCase
{

    public function testConstructor(): void
    {
        //Default values
        $lpq = new ListenerPriorityQueue();
        $preflection1 = $this->getPrivateProtectedAttribute(ListenerPriorityQueue::class, 'storage');
        $preflection2 = $this->getPrivateProtectedAttribute(ListenerPriorityQueue::class, 'queue');
        $this->assertInstanceOf(ListenerPriorityQueue::class, $lpq);
        $this->assertInstanceOf(\SplObjectStorage::class, $preflection1->getValue($lpq));
        $this->assertInstanceOf(\SplPriorityQueue::class, $preflection2->getValue($lpq));

        //Using custom instances
        $storage = $this->getMockBuilder(\SplObjectStorage::class)->getMock();
        $queue = $this->getMockBuilder(\SplPriorityQueue::class)->getMock();
        $lpq = new ListenerPriorityQueue($storage, $queue);
        $preflection1 = $this->getPrivateProtectedAttribute(ListenerPriorityQueue::class, 'storage');
        $preflection2 = $this->getPrivateProtectedAttribute(ListenerPriorityQueue::class, 'queue');
        $this->assertInstanceOf(\SplObjectStorage::class, $preflection1->getValue($lpq));
        $this->assertInstanceOf(\SplPriorityQueue::class, $preflection2->getValue($lpq));
        $this->assertEquals(0, $preflection1->getValue($lpq)->count());
        $this->assertEquals(0, $preflection2->getValue($lpq)->count());
        $this->assertEquals($storage, $preflection1->getValue($lpq));
        $this->assertEquals($queue, $preflection2->getValue($lpq));
    }

    public function testInsert(): void
    {
        $lpq = new ListenerPriorityQueue();
        $preflection1 = $this->getPrivateProtectedAttribute(ListenerPriorityQueue::class, 'storage');
        $preflection2 = $this->getPrivateProtectedAttribute(ListenerPriorityQueue::class, 'queue');
        $storage = $preflection1->getValue($lpq);
        $queue = $preflection2->getValue($lpq);
        $this->assertEquals(0, $storage->count());
        $this->assertEquals(0, $queue->count());

        $listener = new EventListenerTestClass();
        $lpq->insert($listener, 5);
        $storage = $preflection1->getValue($lpq);
        $queue = $preflection2->getValue($lpq);
        $this->assertEquals(1, $storage->count());
        $this->assertEquals(1, $queue->count());
    }

    public function testDetach(): void
    {
        $lpq = new ListenerPriorityQueue();
        $preflection1 = $this->getPrivateProtectedAttribute(ListenerPriorityQueue::class, 'storage');
        $preflection2 = $this->getPrivateProtectedAttribute(ListenerPriorityQueue::class, 'queue');

        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassEmpty();
        $lpq->insert($listener1, 5);
        $lpq->insert($listener2, 50);
        $storage = $preflection1->getValue($lpq);
        $queue = $preflection2->getValue($lpq);
        $this->assertEquals(2, $storage->count());
        $this->assertEquals(2, $queue->count());

        $lpq->detach($listener1);

        $storage = $preflection1->getValue($lpq);
        $queue = $preflection2->getValue($lpq);
        $this->assertEquals(1, $storage->count());
        $this->assertEquals(1, $queue->count());
    }

    public function testClear(): void
    {
        $lpq = new ListenerPriorityQueue();
        $preflection1 = $this->getPrivateProtectedAttribute(ListenerPriorityQueue::class, 'storage');
        $preflection2 = $this->getPrivateProtectedAttribute(ListenerPriorityQueue::class, 'queue');

        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassEmpty();
        $lpq->insert($listener1, 5);
        $lpq->insert($listener2, 50);
        $storage = $preflection1->getValue($lpq);
        $queue = $preflection2->getValue($lpq);
        $this->assertEquals(2, $storage->count());
        $this->assertEquals(2, $queue->count());

        $lpq->clear();

        $storage = $preflection1->getValue($lpq);
        $queue = $preflection2->getValue($lpq);
        $this->assertEquals(0, $storage->count());
        $this->assertEquals(0, $queue->count());
    }

    public function testContains(): void
    {
        $lpq = new ListenerPriorityQueue();

        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassEmpty();
        $lpq->insert($listener1, 5);

        $this->assertTrue($lpq->contains($listener1));
        $this->assertFalse($lpq->contains($listener2));
    }

    public function testReturnAll(): void
    {
        $lpq = new ListenerPriorityQueue();

        $listener1 = new EventListenerTestClass();
        $listener2 = new EventListenerTestClassEmpty();
        $lpq->insert($listener1, 5);
        $lpq->insert($listener2, 2);

        $this->assertEquals(2, count($lpq->all()));

        $lpq->detach($listener1);
        $this->assertEquals(1, count($lpq->all()));

        $lpq->detach($listener2);
        $this->assertEquals(0, count($lpq->all()));
    }
}
