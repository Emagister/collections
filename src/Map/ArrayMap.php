<?php

namespace Emagister\Collections\Map;

use Emagister\Collections\HomogeneityChecker;

/** @extends HMap<array> */
final class ArrayMap extends HMap
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_ARRAY, $elements);
    }

    protected function createSequence(array $elements): ArrayMap
    {
        return new ArrayMap($elements);
    }
}
