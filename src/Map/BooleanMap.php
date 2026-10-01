<?php

namespace Emagister\Collections\Map;

use Emagister\Collections\HomogeneityChecker;

/**
 * @extends SpecificMap<bool>
 */
final class BooleanMap extends SpecificMap
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_BOOLEAN, $elements);
    }
}
