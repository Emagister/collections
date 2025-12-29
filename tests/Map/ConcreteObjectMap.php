<?php

namespace Emagister\Collections\Tests\Map;

use Closure;
use Emagister\Collections\Map\HMap;
use Emagister\Collections\Tests\ConcreteObject;

/**
 * @extends HMap<ConcreteObject>
 */
final class ConcreteObjectMap extends HMap
{
    public function __construct(array $elements = [], Closure $elementKeyClosure = null)
    {
        parent::__construct(ConcreteObject::class, $elements, $elementKeyClosure);
    }
}
