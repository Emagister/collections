<?php

namespace Emagister\Collections\Collection;

use Emagister\Collections\Collection;
use InvalidArgumentException;

abstract class SpecificCollection extends Collection
{
    protected string $classType;

    public function __construct(array $elements = [])
    {
        $this->guardElementsAreSpecificClassType($elements);

        parent::__construct($elements);
    }

    private function guardElementsAreSpecificClassType(array $elements): void
    {
        foreach ($elements as $element) {
            $this->guardElementIsSpecificClassType($element);
        }
    }

    private function guardElementIsSpecificClassType($element): void
    {
        if (!($element instanceof $this->classType)) {
            throw new InvalidArgumentException();
        }
    }
}
