<?php

namespace local_academic_record\shared;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/** @template-implements IteratorAggregate<mixed>*/
abstract class Collection implements Countable, IteratorAggregate
{
    public function __construct(private readonly array $items)
    {
        Assert::arrayOf($this->type(), $items);
    }

    abstract protected function type(): string;

    final public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items());
    }

    final public function count(): int
    {
        return count($this->items());
    }

    protected function items(): array
    {
        return $this->items;
    }
}
