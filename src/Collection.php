<?php

namespace Emagister\Collections;

use Closure;

/**
 * @template TValue
 * @extends Sequence<int, TValue>
 */
class Collection extends Sequence
{
    public function __construct(array $elements = [])
    {
        parent::__construct($elements);

        $this->resetElementKeys();
    }

    private function resetElementKeys(): void
    {
        $this->elements = array_values($this->elements);
    }

    /** @param TValue $element */
    public function add($element): void
    {
        $this->elements[] = $element;
    }

    /** @param TValue $element */
    public function remove($element): bool
    {
        $elementHasBeenRemoved = parent::remove($element);

        if (!$elementHasBeenRemoved) {
            return false;
        }

        $this->resetElementKeys();

        return true;
    }

    final public function each(Closure $callback): void
    {
        foreach ($this->elements as $element) {
            $callback($element);
        }
    }

    final public function join(Collection $collection): Collection
    {
        return new Collection(
            array_merge(
                $this->elements + $collection->toArray()
            )
        );
    }
}
