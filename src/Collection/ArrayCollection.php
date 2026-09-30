<?php

namespace Emagister\Collections\Collection;

use Emagister\Collections\HomogeneityChecker;

/**
 * @extends SpecificCollection<array>
 */
final class ArrayCollection extends SpecificCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_ARRAY, $elements);
    }
}
