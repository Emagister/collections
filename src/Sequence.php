<?php

namespace Emagister\Collections;

use Closure;
use Countable;
use Emagister\Collections\Map\HMap;
use Iterator;
use JsonSerializable;

abstract class Sequence implements JsonSerializable, Iterator, Countable
{
    protected array $elements = [];

    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    public function __clone(): void
    {
        $this->elements = $this->clone()->elements;
    }

    protected function createSequence(array $elements): Sequence
    {
        return new static($elements);
    }

    final public function toArray(): array
    {
        return $this->elements;
    }

    final public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function remove($element): bool
    {
        $elementKey = array_search($element, $this->elements);

        if ($elementKey === false) {
            return false;
        }

        unset($this->elements[$elementKey]);

        return true;
    }

    final public function merge(Sequence $sequence): Sequence
    {
        $this->ensureSequencesAreCompatible($sequence);

        $this->elements = array_merge($this->elements, $sequence->toArray());

        return $this;
    }

    /** @throws CollectionException */
    protected function ensureSequencesAreCompatible(Sequence $sequence): void
    {
        if (get_class($this) !== get_class($sequence)) {
            throw new CollectionException('Sequences types are not compatible');
        }
    }

    final public function count(): int
    {
        return count($this->elements);
    }

    final public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function usort(callable $callback): Sequence
    {
        usort($this->elements, $callback);

        return $this->createSequence($this->elements);
    }

    final public function filter(Closure $callback): Sequence
    {
        return $this->createSequence(
            array_filter($this->elements, $callback, ARRAY_FILTER_USE_BOTH)
        );
    }

    final public function contains($element): bool
    {
        return in_array($element, $this->elements, true);
    }

    final public function containsWithClosure($element, Closure $callback): bool
    {
        return $this->exists(
            function ($sequenceElement) use ($element, $callback) {
                return $callback($element, $sequenceElement);
            }
        );
    }

    final public function equals(Sequence $sequence): bool
    {
        if ($sequence->count() != $this->count()) {
            return false;
        }

        return $sequence->toArray() === $this->toArray();
    }

    /** @throws CollectionException */
    final public function equalsWithClosure(Sequence $sequence, Closure $closure): bool
    {
        if ($sequence->count() != $this->count()) {
            return false;
        }

        return $this->diffWithClosure($sequence, $closure)->isEmpty();
    }

    /** @throws CollectionException */
    final public function diff(Sequence $sequence): Sequence
    {
        $this->ensureSequencesAreCompatible($sequence);

        return $this->createSequence(
            $this->filter(function ($element) use ($sequence) {
                return !$sequence->contains($element);
            })->toArray()
        );
    }

    /** @throws CollectionException */
    final public function diffWithClosure(Sequence $sequence, Closure $callback): Sequence
    {
        $this->ensureSequencesAreCompatible($sequence);

        return $this->filter(
            function ($element) use ($sequence, $callback) {
                return !$sequence->containsWithClosure($element, $callback);
            }
        );
    }

    /** @throws CollectionException */
    final public function intersectWithClosure(Sequence $sequence, Closure $callback): Sequence
    {
        $this->ensureSequencesAreCompatible($sequence);

        return $this->filter(
            function ($element) use ($sequence, $callback) {
                return $sequence->containsWithClosure($element, $callback);
            }
        );
    }

    /** @throws CollectionException */
    final public function partition(Closure $callback): Collection
    {
        $satisfySequence = $this->filter($callback);

        return new Collection([
            $satisfySequence,
            $this->diff($satisfySequence)
        ]);
    }

    final public function head(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        return current(array_slice($this->elements, 0, 1));
    }

    final public function last(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        return current(array_slice($this->elements, -1));
    }

    final public function tail(): Sequence
    {
        return $this->createSequence(
            array_slice($this->elements, 1)
        );
    }

    final public function map(Closure $callback): Collection
    {
        return new Collection(
            array_map($callback, $this->elements)
        );
    }

    final public function exists(Closure $callback): bool
    {
        return !is_null($this->find($callback));
    }

    /** Returns the first element satisfying the callback. */
    final public function find(Closure $callback)
    {
        foreach ($this->elements as $element) {
            if ($callback($element)) {
                return $element;
            }
        }
    }

    /** Returns the first element not satisfying the callback. */
    final public function findNot(Closure $callback)
    {
        foreach ($this->elements as $element) {
            if (!$callback($element)) {
                return $element;
            }
        }
    }

    /** Modifies the current sequence removing the elements satisfying the callback. */
    final public function removeWithClosure(Closure $callback): void
    {
        foreach ($this->elements as $key => $element) {
            if ($callback($element)) {
                unset($this->elements[$key]);
            }
        }
    }

    /** Returns the amount of elements satisfying the callback. */
    final public function countWithClosure(Closure $callback): int
    {
        return $this->filter($callback)->count();
    }

    /** Returns true if all the elements satisfy the callback. */
    final public function forAll(Closure $callback): bool
    {
        return is_null($this->findNot($callback));
    }

    final public function slice(int $offset, ?int $length = null): Sequence
    {
        return $this->createSequence(
            array_slice($this->elements, $offset, $length, true)
        );
    }

    final public function split(int $length): Collection
    {
        $chunks = array_chunk($this->elements, $length, true);

        $result = new Collection();

        foreach ($chunks as $chunk) {
            $result->add(
                $this->createSequence($chunk)
            );
        }

        return $result;
    }

    final public function clone(): Sequence
    {
        $clonedElements = array_map(fn($element) => is_object($element) ? clone $element : $element, $this->elements);

        return $this->createSequence($clonedElements);
    }

    final public function groupBy(Closure $discriminatorCallback): HMap
    {
        $result = new HMap(static::class);

        foreach ($this->elements as $element) {
            $discriminator = $discriminatorCallback($element);
            $discriminatorResult = $result->get($discriminator);

            if (is_null($discriminatorResult)) {
                $discriminatorResult = $this->createSequence([]);
                $result->add($discriminator, $discriminatorResult);
            }

            $discriminatorResult->add($element);
        }

        return $result;
    }

    final public function reduce(Closure $closure, $carry): mixed
    {
        foreach ($this->elements as $element) {
            $carry = $closure($element, $carry);
        }

        return $carry;
    }

    final public function randomElement(): mixed
    {
        return $this->elements[array_rand($this->elements)];
    }

    final public function shuffle(): void
    {
        shuffle($this->elements);
    }

    abstract public function each(Closure $callback): void;
}
