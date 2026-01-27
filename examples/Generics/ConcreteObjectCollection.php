<?php

namespace Emagister\Collections\Examples\Generics;

use Emagister\Collections\Collection\HCollection;

/** @extends HCollection<ConcreteObject> */
final class ConcreteObjectCollection extends HCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(ConcreteObject::class, $elements);
    }
}
