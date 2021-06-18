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
 *  @file ListenerPriorityQueue.php
 *
 *  The ListenerPriorityQueue class used to manage the listener priorities
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

use IteratorAggregate;
use SplObjectStorage;
use SplPriorityQueue;

/**
 * class ListenerPriorityQueue
 * @package Platine\Event
 *
 * @implements IteratorAggregate<ListenerInterface>
 */
class ListenerPriorityQueue implements IteratorAggregate
{

    /**
     * The storage
     * @var SplObjectStorage<ListenerInterface, int>
     */
    protected SplObjectStorage $storage;

    /**
     * The priority queue
     * @var SplPriorityQueue<int, ListenerInterface>
     */
    protected SplPriorityQueue $queue;

    /**
     * Create the new instance
     * @param SplObjectStorage<ListenerInterface, int>|null $storage       the event name
     * @param SplPriorityQueue<int, ListenerInterface>|null  $queue the priority queue
     */
    public function __construct(
        ?SplObjectStorage $storage = null,
        ?SplPriorityQueue $queue = null
    ) {
        $this->storage = $storage ?? new SplObjectStorage();
        $this->queue = $queue ? $queue : new SplPriorityQueue();
    }

    /**
     * Insert an listener to the queue.
     *
     * @param ListenerInterface $listener
     * @param int $priority
     */
    public function insert(ListenerInterface $listener, int $priority): void
    {
        $this->storage->attach($listener, $priority);
        $this->queue->insert($listener, $priority);
    }

    /**
     * Remove an listener from the queue.
     *
     * @param ListenerInterface $listener
     */
    public function detach(ListenerInterface $listener): void
    {
        if ($this->storage->contains($listener)) {
            $this->storage->detach($listener);
            $this->refreshQueue();
        }
    }

    /**
     * Clear the queue
     * @return void
     */
    public function clear(): void
    {
        $this->storage = new SplObjectStorage();
        $this->queue = new SplPriorityQueue();
    }

    /**
     * Checks whether the queue contains the listener.
     *
     * @param ListenerInterface $listener
     * @return bool
     */
    public function contains(ListenerInterface $listener): bool
    {
        return $this->storage->contains($listener);
    }

    /**
     * Return all listeners
     * @return SplPriorityQueue<int, ListenerInterface>[]
     */
    public function all(): array
    {
        $listeners = [];
        foreach ($this->getIterator() as $listener) {
            $listeners[] = $listener;
        }

        return $listeners;
    }

    /**
     * Clones and returns a iterator.
     *
     * @return SplPriorityQueue<int, ListenerInterface>
     */
    public function getIterator(): SplPriorityQueue
    {
        $queue = clone $this->queue;
        if (!$queue->isEmpty()) {
            $queue->top();
        }

        return $queue;
    }

    /**
     * Refresh the status of the queue
     * @return void
     */
    protected function refreshQueue(): void
    {
        $this->storage->rewind();
        $this->queue = new SplPriorityQueue();
        foreach ($this->storage as $listener) {
            $priority = $this->storage->getInfo();
            $this->queue->insert($listener, $priority);
        }
    }
}
