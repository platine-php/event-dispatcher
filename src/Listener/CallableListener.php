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
 *  @file CallableListener.php
 *
 *  This class is used to manager listener using callable
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

namespace Platine\Event\Listener;

use Platine\Event\EventInterface;

/**
 * @class CallableListener
 * @package Platine\Event\Listener
 */
class CallableListener implements ListenerInterface
{
    /**
     * The callable
     * @var callable
     */
    protected $callable;

    /**
     * The listeners
     * @var array<$this>
     */
    protected static $listeners = [];

    /**
     * Create new instance
     * @param  callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
        static::$listeners[] = $this;
    }

    /**
     * Return the callable
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * Create new instance using the given callable
     * @param  callable $callable the callable
     * @return self the new instance
     */
    public static function fromCallable(callable $callable): self
    {
        $listener = new self($callable);

        return $listener;
    }

    /**
     * Search the listener for given callable
     * @param  callable $callable the callable
     * @return $this|false
     */
    public static function getListener(callable $callable): self|false
    {
        foreach (static::$listeners as $listener) {
            if ($listener->getCallable() == $callable) {
                return $listener;
            }
        }

        return false;
    }

    /**
     * Removes all registered callable-listeners.
     * @return void
     */
    public static function clear(): void
    {
        static::$listeners = [];
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function handle(EventInterface $event): mixed
    {
        ($this->callable)($event);

        return true;
    }
}
