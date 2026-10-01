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

To write a typed collection whose constructor takes the elements as its first parameter, extend `SpecificCollection`
(or `SpecificMap`) and pass the type to the parent constructor:

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

The constructor can take more parameters, as long as they are optional. Keep in mind that the sequences returned by
`filter()`, `slice()`, etc. are created with the default values of those parameters, not with the values of the
original sequence.

If the constructor cannot be called as shown in the table above, because it has other required parameters or takes
its arguments in a different order, you must also override `createSequence()`. Otherwise those methods throw a
`CollectionException` telling you to do so. Override it as well if the sequences returned by those methods must keep
the values of the optional parameters. For example, for a constructor
`__construct(string $type, array $elements, Logger $logger)`:

```php
protected function createSequence(array $elements): static
{
    return new static($this->type(), $elements, $this->logger);
}
```
