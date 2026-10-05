<?php

namespace Emagister\Collections;

trait ComparatorBehavior
{
    public function reversed(): Comparator
    {
        return new ReverseComparator($this);
    }
}
