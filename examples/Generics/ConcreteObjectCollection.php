<?php

namespace Emagister\Collections\Examples\Generics;

use Emagister\Collections\Collection\SpecificCollection;

/** @extends SpecificCollection<ConcreteObject> */
final class ConcreteObjectCollection extends SpecificCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(ConcreteObject::class, $elements);
    }
}
