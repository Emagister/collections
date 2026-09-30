Emagister Collections
=====================

Writing your own typed collection
---------------------------------

Methods that return a new sequence (`filter()`, `slice()`, `tail()`, `diff()`, `clone()`, ...) build it through
`createSequence()`, which calls the constructor of the current class:

| Base class                           | `createSequence()` calls            |
|--------------------------------------|-------------------------------------|
| `Collection`, `Map`                  | `new static($elements)`             |
| `HCollection`, `HMap`                | `new static($type, $elements)`      |
| `SpecificCollection`, `SpecificMap`  | `new static($elements)`             |

To write a typed collection whose constructor takes only the elements, extend `SpecificCollection` (or `SpecificMap`)
and pass the type to the parent constructor:

```php
/** @extends SpecificCollection<ConcreteObject> */
final class ConcreteObjectCollection extends SpecificCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(ConcreteObject::class, $elements);
    }
}
```

If you redefine the constructor with any other signature, you must also override `createSequence()`.
Otherwise those methods throw a `CollectionException` telling you to do so. For example, for a constructor
`__construct(string $type, array $elements, Logger $logger)`:

```php
protected function createSequence(array $elements): static
{
    return new static($this->type(), $elements, $this->logger);
}
```
