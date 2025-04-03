<?php

/**
 * Platine Event Dispatcher
 *
 * Platine Event Dispatcher is the minimal implementation of PSR 14
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2020 Platine Event Dispatcher
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *  @file Dispatcher.php
 *
 *  The Event Dispatcher class used to manage the event dispatcher, listener, subscriber
 *
 *  @package    Platine\Event
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   https://www.platine-php.com
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Event;

use Platine\Event\Listener\CallableListener;
use Platine\Event\Listener\ListenerInterface;
use Platine\Event\Listener\ListenerPriorityQueue;

/**
 * @class Dispatcher
 * @package Platine\Event
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * The list of listener
     * @var array<string, ListenerPriorityQueue>
     */
    protected array $listeners = [];

    /**
     * {@inheritdoc}
     */
    public function dispatch(
        string|EventInterface $eventName,
        ?EventInterface $event = null
    ): EventInterface {
        if ($eventName instanceof EventInterface) {
            $event = $eventName;
        } elseif (is_null($event)) {
            $event = new Event($eventName);
        }

        if (isset($this->listeners[$event->getName()])) {
            foreach ($this->listeners[$event->getName()] as $listener) {
                if ($event->isStopPropagation()) {
                    break;
                }

                ([$listener, 'handle'])($event);
            }
        }

        return $event;
    }

    /**
     * {@inheritdoc}
     */
    public function addListener(
        string $eventName,
        ListenerInterface|callable $listener,
        int $priority = self::PRIORITY_DEFAULT
    ): void {
        if (!isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = new ListenerPriorityQueue();
        }

        if (is_callable($listener)) {
            $listener = CallableListener::fromCallable($listener);
        }

        $this->listeners[$eventName]->insert($listener, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscriber(SubscriberInterface $subscriber): void
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $action) {
            $this->addListener($eventName, [$subscriber, $action]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener(string $eventName, ListenerInterface|callable $listener): void
    {
        if (empty($this->listeners[$eventName])) {
            return;
        }

        if (is_callable($listener)) {
            $listener = CallableListener::getListener($listener);
        }

        if ($listener === false) {
            return;
        }

        $this->listeners[$eventName]->detach($listener);
    }

    /**
     * {@inheritdoc}
     */
    public function removeSubscriber(SubscriberInterface $subscriber): void
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $action) {
            $this->removeListener($eventName, [$subscriber, $action]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllListener(?string $eventName = null): void
    {
        if (!is_null($eventName) && isset($this->listeners[$eventName])) {
            $this->listeners[$eventName]->clear();
        } else {
            foreach ($this->listeners as $queue) {
                $queue->clear();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasListener(string $eventName, ListenerInterface|callable $listener): bool
    {
        if (!isset($this->listeners[$eventName])) {
            return false;
        }

        if (is_callable($listener)) {
            $listener = CallableListener::getListener($listener);
        }

        if ($listener === false) {
            return false;
        }

        return $this->listeners[$eventName]->contains($listener);
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners(?string $eventName = null): array
    {
        if (!is_null($eventName)) {
            return isset($this->listeners[$eventName])
                    ? $this->listeners[$eventName]->all()
                    : [];
        } else {
            $listeners = [];
            foreach ($this->listeners as $queue) {
                $listeners = array_merge($listeners, $queue->all());
            }

            return $listeners;
        }
    }
}
