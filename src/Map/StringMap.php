<?php

namespace Emagister\Collections\Map;

use Emagister\Collections\CollectionException;
use Emagister\Collections\HomogeneityChecker;

/**
 * @extends SpecificMap<string>
 */
class StringMap extends SpecificMap
{
    public const ORDER_ASC = 'ASC';
    public const ORDER_DESC = 'DESC';

    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_STRING, $elements);
    }

    /** @throws CollectionException */
    public function sortAlphabetically(string $order = self::ORDER_ASC): static
    {
        return $this->usort(function (string $a, string $b) use ($order) {
            $comparison = strcmp(
                iconv('UTF-8', 'ASCII//TRANSLIT', $a),
                iconv('UTF-8', 'ASCII//TRANSLIT', $b)
            );

            return $comparison * ($order == self::ORDER_ASC ? 1 : -1);
        });
    }
}
