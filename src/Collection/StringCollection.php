<?php

namespace Emagister\Collections\Collection;

use Emagister\Collections\HomogeneityChecker;
use Stringable;

final class StringCollection extends HCollection implements Stringable
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_STRING, $elements);
    }

    protected function createSequence(array $elements): StringCollection
    {
        return new StringCollection($elements);
    }

    public function __toString(): string
    {
        return implode(',', $this->elements);
    }
}
