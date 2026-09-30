<?php

namespace Emagister\Collections\Collection;

/**
 * Base class for typed collections whose constructor takes only the elements.
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
