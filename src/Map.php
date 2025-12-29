<?php

namespace Emagister\Collections;

use Closure;

/**
 * @template TValue
 * @extends Sequence<string, TValue>
 */
class Map extends Sequence
{
    public function __construct(array $elements = [], Closure $elementKeyClosure = null)
    {
        if (!is_null($elementKeyClosure)) {
            $elements = $this->indexElementsUsingClosure($elements, $elementKeyClosure);
        }

        parent::__construct($elements);
    }

    /**
     * @param array   $elements
     * @param Closure $elementKeyClosure
     *
     * @return array<string, TValue>
     */
    private function indexElementsUsingClosure(array $elements, Closure $elementKeyClosure): array
    {
        $keys = array_map(
            function ($element) use ($elementKeyClosure) {
                return $elementKeyClosure($element);
            },
            $elements
        );

        return array_combine($keys, $elements);
    }

    /**
     * @param string $key
     * @param TValue $value
     */
    public function add(string $key, $value): void
    {
        $this->elements[$key] = $value;
    }

    /**
     * @param string $key
     * @param TValue $value
     */
    public function prepend(string $key, $value): void
    {
        $currentElements = $this->elements;
        $this->elements = [];

        $this->add($key, $value);

        foreach ($currentElements as $elementKey => $elementValue) {
            $this->add($elementKey, $elementValue);
        }
    }

    public function has(string $key): bool
    {
        return array_key_exists(
            $key,
            $this->elements
        );
    }

    /** @deprecated Use `has` instead */
    final public function containsKey(string $key): bool
    {
        return $this->has($key);
    }

    final public function get(string $key, $defaultValue = null)
    {
        if ($this->has($key) && !is_null($this->elements[$key])) {
            return $this->elements[$key];
        }

        return $defaultValue;
    }

    final public function getByKeys(string ...$keys): Map
    {
        return $this->filterKeys(
            function ($key) use ($keys) {
                return in_array(
                    $key,
                    $keys
                );
            }
        );
    }

    final public function removeKey(string $key): bool
    {
        if (!$this->has($key)) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }

    /** @return array<string> */
    final public function keys(): array
    {
        return array_map(fn($key) => (string)$key, array_keys($this->elements));
    }

    final public function usort(callable $callback): Map
    {
        $elements = $this->elements;

        uasort($elements, $callback);

        return $this->createSequence($elements);
    }

    final public function filterKeys(Closure $closure): Map
    {
        return $this->filter(
            function ($_, $key) use ($closure) {
                return $closure($key);
            }
        );
    }

    final public function each(Closure $callback): void
    {
        foreach ($this->elements as $key => $element) {
            $callback($element, $key);
        }
    }
}
