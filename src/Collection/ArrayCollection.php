<?php

namespace Emagister\Collections\Collection;

use Emagister\Collections\HomogeneityChecker;

final class ArrayCollection extends HCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_ARRAY, $elements);
    }

    protected function createSequence(array $elements): ArrayCollection
    {
        return new ArrayCollection($elements);
    }
}
