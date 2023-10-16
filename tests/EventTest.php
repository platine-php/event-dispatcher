<?php

declare(strict_types=1);

namespace Platine\Test\Event;

use Platine\Event\Event;
use Platine\Dev\PlatineTestCase;

/**
 * Event class tests
 *
 * @group core
 * @group event
 */
class EventTest extends PlatineTestCase
{
    public function testConstructor(): void
    {
        $e = new Event('foo_event');
        $er = $this->getPrivateProtectedAttribute(Event::class, 'arguments');
        $this->assertInstanceOf(Event::class, $e);
        $this->assertEmpty($er->getValue($e));

        $arguments = array('foo' => 'bar');
        $e = new Event('foo_event', $arguments);
        $this->assertNotEmpty($er->getValue($e));
        $this->assertArrayHasKey('foo', $er->getValue($e));
    }

    public function testSetGetName(): void
    {
        $e = new Event('foo_event');
        $this->assertEquals('foo_event', $e->getName());

        $e->setName('bar_event');
        $this->assertEquals('bar_event', $e->getName());
    }

    public function testSetGetArguments(): void
    {
        $arguments = array('foo' => 'bar');
        $e = new Event('foo_event', $arguments);
        $this->assertNotEmpty($e->getArguments());
        $this->assertArrayHasKey('foo', $e->getArguments());

        $e->setArguments(array('bar' => 'foo'));
        $this->assertNotEmpty($e->getArguments());
        $this->assertArrayHasKey('bar', $e->getArguments());
    }

    public function testSetGetArgument(): void
    {
        $arguments = array('foo' => 'bar');
        $e = new Event('foo_event', $arguments);
        $this->assertEquals('bar', $e->getArgument('foo'));
        $this->assertNull($e->getArgument('not_exist'));

        $e->setArgument('bar', 12);
        $this->assertEquals(12, $e->getArgument('bar'));
    }

    public function testEventPropagation(): void
    {
        $e = new Event('foo_event');
        $this->assertFalse($e->isStopPropagation());

        $e->stopPropagation();
        $this->assertTrue($e->isStopPropagation());
    }
}
