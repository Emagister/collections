<?php

namespace Emagister\Collections\Map;

use Emagister\Collections\HomogeneityChecker;

/** @extends HMap<string, bool> */
final class BooleanMap extends HMap
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_BOOLEAN, $elements);
    }

    protected function createSequence(array $elements): BooleanMap
    {
        return new BooleanMap($elements);
    }
}
