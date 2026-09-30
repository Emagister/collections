<?php

namespace Emagister\Collections\Examples\Generics;

use Closure;
use Emagister\Collections\Map\SpecificMap;

/** @extends SpecificMap<ConcreteObject> */
final class ConcreteObjectMap extends SpecificMap
{
    public function __construct(array $elements = [], ?Closure $elementKeyClosure = null)
    {
        parent::__construct(ConcreteObject::class, $elements, $elementKeyClosure);
    }
}
