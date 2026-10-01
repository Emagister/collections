<?php

namespace Emagister\Collections\Collection;

use Emagister\Collections\HomogeneityChecker;
use Stringable;

/**
 * @extends SpecificCollection<string>
 */
final class StringCollection extends SpecificCollection implements Stringable
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_STRING, $elements);
    }

    public function __toString(): string
    {
        return implode(',', $this->elements);
    }
}
