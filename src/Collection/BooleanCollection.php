<?php

namespace Emagister\Collections\Collection;

use Emagister\Collections\HomogeneityChecker;

/**
 * @extends SpecificCollection<bool>
 */
final class BooleanCollection extends SpecificCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_BOOLEAN, $elements);
    }
}
