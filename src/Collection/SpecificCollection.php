<?php

namespace Emagister\Collections\Collection;

/**
 * Base class for typed collections whose constructor takes the elements as its first parameter,
 * and no other required parameters.
 *
 * @template TValue
 *
 * @extends HCollection<TValue>
 */
abstract class SpecificCollection extends HCollection
{
    protected function createSequence(array $elements): static
    {
        return $this->newInstance($elements);
    }
}
