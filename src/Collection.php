<?php

namespace Emagister\Collections;

use Closure;

class Collection extends Sequence
{
    private ?int $currentIndex;

    public function __construct(array $elements = [])
    {
        parent::__construct($elements);

        $this->currentIndex = 0;
        $this->resetElementKeys();
    }

    private function resetElementKeys(): void
    {
        $this->elements = array_values($this->elements);
    }

    public function add($element): void
    {
        $this->elements[] = $element;
    }

    public function remove($element): bool
    {
        $elementKey = array_search($element, $this->elements);
        $elementHasBeenRemoved = parent::remove($element);

        if (!$elementHasBeenRemoved) {
            return false;
        }

        if ($elementKey === 0 && $this->currentIndex === 0) {
            $this->currentIndex = null;
        } elseif (!is_null($this->currentIndex) && $elementKey <= $this->currentIndex) {
            $this->currentIndex--;
        }

        $this->resetElementKeys();

        return true;
    }

    final public function current(): mixed
    {
        if (empty($this->elements)) {
            return null;
        }

        return $this->elements[$this->currentIndex ?? 0];
    }

    final public function next(): void
    {
        if (is_null($this->currentIndex)) {
            $this->currentIndex = 0;

            return;
        }

        $this->currentIndex++;
    }

    final public function key(): int
    {
        return $this->currentIndex;
    }

    final public function valid(): bool
    {
        return isset($this->elements[$this->currentIndex ?? 0]);
    }

    final public function rewind(): void
    {
        $this->currentIndex = 0;
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
