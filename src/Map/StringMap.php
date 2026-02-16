<?php

namespace Emagister\Collections\Map;

use Emagister\Collections\HomogeneityChecker;

/** @extends HMap<string> */
class StringMap extends HMap
{
    public const ORDER_ASC = 'ASC';
    public const ORDER_DESC = 'DESC';

    public function __construct(array $elements = [])
    {
        parent::__construct(HomogeneityChecker::TYPE_STRING, $elements);
    }

    protected function createSequence(array $elements): StringMap
    {
        return new StringMap($elements);
    }

    public function sortAlphabetically(string $order = self::ORDER_ASC): StringMap
    {
        /** @var StringMap $sortedMap */
        $sortedMap = $this->usort(function (string $a, string $b) use ($order) {
            $comparison = strcmp(
                iconv('UTF-8', 'ASCII//TRANSLIT', $a),
                iconv('UTF-8', 'ASCII//TRANSLIT', $b)
            );

            return $comparison * ($order == self::ORDER_ASC ? 1 : -1);
        });

        return $sortedMap;
    }
}
