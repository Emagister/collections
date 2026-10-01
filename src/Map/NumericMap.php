<?php

namespace Emagister\Collections\Map;

use Emagister\Collections\HomogeneityChecker;

/**
 * @extends SpecificMap<numeric>
 */
final class NumericMap extends SpecificMap
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_NUMERIC, $elements);
    }
}
