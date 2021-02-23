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
 *  @file DispatcherInterface.php
 *
 *  The Event Dispacther interface
 *
 *  @package    Platine\Event
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   http://www.iacademy.cf
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Event;

interface DispatcherInterface
{

    /**
     * The low priority
     * @var int
     */
    public const PRIORITY_LOW = -100;

    /**
     * The default priority
     * @var int
     */
    public const PRIORITY_DEFAULT = 0;

    /**
     * The high priority
     * @var int
     */
    public const PRIORITY_HIGH = 100;

    /**
     * Dispatches an event to all registered listeners.
     * @param  string|EventInterface     $eventName the name of event of instance of EventInterface
     * @param  EventInterface|null $event  the instance of EventInterface or null
     * @return void
     */
    public function dispatch($eventName, EventInterface $event = null): void;

    /**
     * Register a listener for the given event.
     *
     * @param string $eventName the name of event
     * @param ListenerInterface|callable $listener the ListenerInterface or any callable
     * @param int $priority the listener execution priority
     */
    public function addListener(string $eventName, $listener, int $priority = self::PRIORITY_DEFAULT): void;

    /**
     * Register a subscriber.
     *
     * @param subscriberInterface $subscriber the subscriberInterface instance
     */
    public function addSubscriber(subscriberInterface $subscriber): void;

    /**
     * Remove a listener for the given event.
     *
     * @param string $eventName the name of event
     * @param ListenerInterface|callable $listener the ListenerInterface or any callable
     */
    public function removeListener(string $eventName, $listener): void;

    /**
     * Remove a subscriber.
     *
     * @param subscriberInterface $subscriber the subscriberInterface instance
     */
    public function removeSubscriber(subscriberInterface $subscriber): void;

    /**
     * Remove all listener for the given event.
     *
     * @param string $eventName the name of event
     */
    public function removeAllListener(string $eventName = null): void;

    /**
     * Check whether the listener exists for the given event.
     *
     * @param string $eventName the name of event
     * @param ListenerInterface|callable $listener the ListenerInterface or any callable
     *
     * @return bool
     */
    public function hasListener(string $eventName, $listener): bool;

    /**
     * Get all listeners for the given event or all registered listeners.
     *
     * @param string $eventName the name of event
     * @return array
     */
    public function getListeners(string $eventName = null): array;
}
