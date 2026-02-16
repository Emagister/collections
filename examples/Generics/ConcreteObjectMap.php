<?php

namespace Emagister\Collections\Examples\Generics;

use Closure;
use Emagister\Collections\Map\HMap;

/** @extends HMap<ConcreteObject> */
final class ConcreteObjectMap extends HMap
{
    public function __construct(array $elements = [], Closure $elementKeyClosure = null)
    {
        parent::__construct(ConcreteObject::class, $elements, $elementKeyClosure);
    }
}
