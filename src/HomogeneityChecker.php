<?php

namespace Emagister\Collections;

final class HomogeneityChecker
{
    public const TYPE_STRING = 'string';
    public const TYPE_BOOLEAN = 'bool';
    public const TYPE_ARRAY = 'array';
    public const TYPE_NUMERIC = 'numeric';

    private string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /** @throws HomogeneityException */
    public function checkElements(array $elements): void
    {
        foreach ($elements as $element) {
            $this->checkElement($element);
        }
    }

    /** @throws HomogeneityException */
    public function checkElement($element): void
    {
        if (!is_object($element) && !$this->basicElementTypeIsCompatible($element)) {
            $this->throwException($element);
        }

        if (is_object($element) && !$this->elementTypeIsCompatible($element)) {
            $this->throwException($element);
        }
    }

    public function elementType($element): string
    {
        if (is_object($element)) {
            return get_class($element);
        }

        return gettype($element);
    }

    private function basicElementTypeIsCompatible($element): bool
    {
        switch ($this->type) {
            case self::TYPE_STRING:
                return is_string($element);

            case self::TYPE_BOOLEAN:
                return is_bool($element);

            case self::TYPE_ARRAY:
                return is_array($element);

            case self::TYPE_NUMERIC:
                return !is_string($element) && is_numeric($element);

            default:
                return false;
        }
    }

    private function elementTypeIsCompatible($element): bool
    {
        if ($this->elementIsSameType($element)) {
            return true;
        }

        return $this->elementIsSubtype($element);
    }

    private function elementIsSameType($element): bool
    {
        return get_class($element) == $this->type;
    }

    private function elementIsSubtype($element): bool
    {
        if (in_array($this->type, class_implements($element))) {
            return true;
        }

        return in_array($this->type, class_parents($element));
    }

    /** @throws HomogeneityException */
    private function throwException($element): void
    {
        throw new HomogeneityException(
            sprintf(
                'This sequence can only hold elements of type %s, %s given',
                $this->type,
                is_object($element) ? get_class($element) : gettype($element)
            )
        );
    }
}
