<?php

namespace Emagister\Collections\Collection;

use Emagister\Collections\HomogeneityChecker;

/** @extends HCollection<int, bool> */
final class BooleanCollection extends HCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_BOOLEAN, $elements);
    }

    protected function createSequence(array $elements): BooleanCollection
    {
        return new BooleanCollection($elements);
    }
}
