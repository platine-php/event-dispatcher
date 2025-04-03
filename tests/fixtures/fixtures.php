<?php

declare(strict_types=1);

namespace Platine\Test\Fixture;

use Platine\Event\EventInterface;
use Platine\Event\Listener\ListenerInterface;
use Platine\Event\SubscriberInterface;

class EventListenerTestClass implements ListenerInterface
{
    public function handle(EventInterface $event): mixed
    {
        echo $event->getName();
        return true;
    }
}

class EventListenerTestClassEmpty implements ListenerInterface
{
    public function handle(EventInterface $event): mixed
    {
        return true;
    }
}

class EventListenerTestClassEventInstanceChanged implements ListenerInterface
{
    public function handle(EventInterface $event): mixed
    {
        $event->setArgument('foo', 'bar');
        return true;
    }
}

class EventListenerTestClassStopPropagation implements ListenerInterface
{
    public function handle(EventInterface $event): mixed
    {
        $event->stopPropagation();
        return true;
    }
}

class EventSubscriberTestClass implements SubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return array(
            'foo_event_subs' => 'onFooEvent',
            'bar_event_subs' => 'onBarEvent',
        );
    }

    public function onFooEvent(EventInterface $event)
    {
    }

    public function onBarEvent(EventInterface $event)
    {
    }
}


function callable_listener(EventInterface $event)
{
    echo $event->getName();
}
