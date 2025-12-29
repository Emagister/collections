<?php

namespace Emagister\Collections\Map;

use Emagister\Collections\HomogeneityChecker;

/** @extends HMap<string, numeric> */
final class NumericMap extends HMap
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_NUMERIC, $elements);
    }

    protected function createSequence(array $elements): NumericMap
    {
        return new NumericMap($elements);
    }
}
