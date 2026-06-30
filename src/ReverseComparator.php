<?php

namespace Emagister\Collections;

final class ReverseComparator implements Comparator
{
    public function __construct(private readonly Comparator $inner) {}

    public function compare(mixed $a, mixed $b): int
    {
        return $this->inner->compare($b, $a);
    }

    public function reversed(): Comparator
    {
        return $this->inner;
    }
}
