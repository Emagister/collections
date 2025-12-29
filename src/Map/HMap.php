<?php

namespace Emagister\Collections\Map;

use Closure;
use Emagister\Collections\CollectionException;
use Emagister\Collections\HomogeneityChecker;
use Emagister\Collections\Map;
use Emagister\Collections\Sequence;

/**
 * @template TValue
 * @extends Map<TValue>
 */
class HMap extends Map
{
    private HomogeneityChecker $homogeneityChecker;

    private string $type;

    /** @throws CollectionException */
    public function __construct(string $type, array $elements = [], Closure $elementKeyClosure = null)
    {
        $this->type = $type;
        $this->homogeneityChecker = new HomogeneityChecker($type);

        $this->homogeneityChecker->checkElements($elements);

        parent::__construct($elements, $elementKeyClosure);
    }

    /**
     * @param TValue $value
     * @throws CollectionException
     */
    public function add(string $key, $value): void
    {
        $this->homogeneityChecker->checkElement($value);

        parent::add($key, $value);
    }

    final public function type(): string
    {
        return $this->type;
    }

    /**
     * @param array<string, TValue> $elements
     * @return HMap<TValue>
     * @throws CollectionException
     */
    protected function createSequence(array $elements): HMap
    {
        return new static($this->type, $elements);
    }

    /**
     * @throws CollectionException
     */
    protected function ensureSequencesAreCompatible(Sequence $sequence): void
    {
        parent::ensureSequencesAreCompatible($sequence);

        $this->homogeneityChecker->checkElements($sequence->toArray());
    }
}
