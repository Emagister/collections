<?php

namespace Emagister\Collections\Map;

use Emagister\Collections\HomogeneityChecker;

/**
 * @extends SpecificMap<array>
 */
final class ArrayMap extends SpecificMap
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_ARRAY, $elements);
    }
}
