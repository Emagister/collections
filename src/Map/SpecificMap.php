<?php

namespace Emagister\Collections\Map;

/**
 * Base class for typed maps whose constructor takes only the elements.
 *
 * @template TValue
 *
 * @extends HMap<TValue>
 */
abstract class SpecificMap extends HMap
{
    protected function createSequence(array $elements): static
    {
        return $this->newInstance($elements);
    }
}
