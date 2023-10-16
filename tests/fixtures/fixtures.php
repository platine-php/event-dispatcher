<?php

declare(strict_types=1);

namespace Platine\Test\Fixture;

class EventListenerTestClass implements \Platine\Event\ListenerInterface
{
    public function handle(\Platine\Event\EventInterface $event)
    {
        echo $event->getName();
    }
}

class EventListenerTestClassEmpty implements \Platine\Event\ListenerInterface
{
    public function handle(\Platine\Event\EventInterface $event)
    {
    }
}

class EventListenerTestClassEventInstanceChanged implements \Platine\Event\ListenerInterface
{
    public function handle(\Platine\Event\EventInterface $event)
    {
        $event->setArgument('foo', 'bar');
    }
}

class EventListenerTestClassStopPropagation implements \Platine\Event\ListenerInterface
{
    public function handle(\Platine\Event\EventInterface $event)
    {
        $event->stopPropagation();
    }
}

class EventSubscriberTestClass implements \Platine\Event\SubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return array(
            'foo_event_subs' => 'onFooEvent',
            'bar_event_subs' => 'onBarEvent',
        );
    }

    public function onFooEvent(\Platine\Event\EventInterface $event)
    {
    }

    public function onBarEvent(\Platine\Event\EventInterface $event)
    {
    }
}


function callable_listener(\Platine\Event\EventInterface $event)
{
    echo $event->getName();
}
