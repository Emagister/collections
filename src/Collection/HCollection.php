<?php

namespace Emagister\Collections\Collection;

use Emagister\Collections\Collection;
use Emagister\Collections\CollectionException;
use Emagister\Collections\HomogeneityChecker;
use Emagister\Collections\Sequence;

class HCollection extends Collection
{
    private HomogeneityChecker $homogeneityChecker;

    private string $type;

    /** @throws CollectionException */
    public function __construct(string $type, array $elements = [])
    {
        $this->type = $type;
        $this->homogeneityChecker = new HomogeneityChecker($type);

        $this->homogeneityChecker->checkElements($elements);

        parent::__construct($elements);
    }

    /** @throws CollectionException */
    public function add($element): void
    {
        $this->homogeneityChecker->checkElement($element);

        parent::add($element);
    }

    final public function type(): string
    {
        return $this->type;
    }

    /** @throws CollectionException */
    protected function createSequence(array $elements): HCollection
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
