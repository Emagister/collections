<?php

namespace Emagister\Collections;

interface Comparator
{
    public function compare(mixed $a, mixed $b): int;

    public function reversed(): Comparator;
}
