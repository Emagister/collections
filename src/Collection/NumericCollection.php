<?php

namespace Emagister\Collections\Collection;

use Emagister\Collections\HomogeneityChecker;
use Stringable;

/**
 * @extends SpecificCollection<numeric>
 */
final class NumericCollection extends SpecificCollection implements Stringable
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_NUMERIC, $elements);
    }

    public function __toString(): string
    {
        return implode(',', $this->elements);
    }
}
