<?php

namespace Emagister\Collections\Collection;

use Emagister\Collections\HomogeneityChecker;
use Stringable;

final class NumericCollection extends HCollection implements Stringable
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_NUMERIC, $elements);
    }

    protected function createSequence(array $elements): NumericCollection
    {
        return new NumericCollection($elements);
    }

    public function __toString(): string
    {
        return implode(',', $this->elements);
    }
}
