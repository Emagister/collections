<?php

namespace Emagister\Collections\Tests\Collection;

use Emagister\Collections\Collection\HCollection;
use Emagister\Collections\Tests\ConcreteObject;

/** @extends HCollection<ConcreteObject> */
final class ConcreteObjectCollection extends HCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(ConcreteObject::class, $elements);
    }
}
