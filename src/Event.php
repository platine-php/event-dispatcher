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
 *  @file Event.php
 *
 *  The Event class used to contains information about event to dispatch to
 *  listeners or subscribers
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

class Event implements EventInterface
{

    /**
     * The event name
     * @var string
     */
    protected string $name;

    /**
     * The event data
     * @var array
     */
    protected array $arguments = [];

    /**
     * Whether the propagation is stopped.
     * @var boolean
     */
    protected bool $stopPropagation = false;

    /**
     * Create the new instance of the Event
     * @param string $name       the event name
     * @param array  $arguments the event data
     */
    public function __construct(string $name, array $arguments = [])
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the event name.
     *
     * @param string $name the new event name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get all event data or arguments.
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Set array of arguments.
     *
     * @param array $arguments
     *
     * @return self
     */
    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * Get event data for the given key.
     * @param string $key
     * @return mixed
     */
    public function getArgument(string $key)
    {
        return array_key_exists($key, $this->arguments) ? $this->arguments[$key] : null;
    }

    /**
     * Set event data for the given key.
     *
     * @param string $key
     * @param mixed $value the event value
     *
     * @return self
     */
    public function setArgument(string $key, $value): self
    {
        $this->arguments[$key] = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isStopPropagation(): bool
    {
        return $this->stopPropagation;
    }

    /**
     * {@inheritdoc}
     */
    public function stopPropagation(): self
    {
        $this->stopPropagation = true;
        return $this;
    }
}
