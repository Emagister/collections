Upgrading
=========

From 1.x to 2.0
---------------

Methods returning a new sequence now declare a `static` return type, so they return the class you called them on
instead of `Sequence`, `Collection` or `Map`. Code calling these methods keeps working. Only subclasses may need changes.

### Overrides of `createSequence()`, `usort()` or `sortAlphabetically()` must return `static`

`Sequence::createSequence()`, `Sequence::usort()` and `StringMap::sortAlphabetically()` now return `static`. PHP
refuses to load a subclass that overrides them with any other return type, even a `final` one:

```
Fatal error: Declaration of MyCollection::createSequence(): MyCollection must be compatible with
HCollection::createSequence(): static
```

Change the return type of the override to `static`:

```php
// Before
protected function createSequence(array $elements): MyCollection
{
    return new MyCollection($elements);
}

// After
protected function createSequence(array $elements): static
{
    return new MyCollection($elements);
}
```

If the constructor takes only the elements, you can drop the override instead and extend `SpecificCollection` or
`SpecificMap`, which build new instances with `new static($elements)`.

### `newInstance()` is now a final method of `Sequence`

`Sequence` defines `final protected function newInstance(mixed ...$arguments): static`. A subclass defining its own
`newInstance()` method no longer loads. Rename that method.

### `CollectionException` instead of `TypeError` when a sequence cannot be created

When `createSequence()` cannot call the constructor of your class, because it was redefined with a different
signature, derived-sequence methods (`filter()`, `slice()`, `tail()`, ...) now throw a `CollectionException` asking you
to override `createSequence()`. It used to be a `TypeError`. The original `TypeError` is available through
`getPrevious()`.

The fix is to extend `SpecificCollection`/`SpecificMap` or to override `createSequence()`, as described in the README
section [Writing your own typed collection](README.md#writing-your-own-typed-collection).

### `SpecificCollection` is now a base class for typed collections

`SpecificCollection` used to extend `Collection` and check its elements against a `$classType` property, only in the
constructor. It now extends `HCollection`, which checks elements both in the constructor and in `add()`. The
`$classType` property is gone: pass the type to the parent constructor instead.

```php
// Before
final class UserCollection extends SpecificCollection
{
    protected string $classType = User::class;
}

// After
final class UserCollection extends SpecificCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(User::class, $elements);
    }
}
```

Elements of the wrong type now throw a `HomogeneityException` instead of an `InvalidArgumentException`.
