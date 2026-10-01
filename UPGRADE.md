Upgrading
=========

From 1.x to 2.0
---------------

Methods returning a new sequence now declare a `static` return type, so they return the class you called them on
instead of `Sequence`, `Collection` or `Map`. Code calling these methods keeps working, except for `usort()` and
`merge()`, which no longer modify the original sequence, and the removed `Collection::join()`. Otherwise, only
subclasses may need changes.

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

If the constructor takes the elements as its first parameter and has no other required parameters, you can drop the
override instead and extend `SpecificCollection` or `SpecificMap`, which build new instances with
`new static($elements)`.

### `Collection::usort()` no longer sorts the original collection

`usort()` on a collection used to sort the collection it was called on, besides returning a new sorted one. It now
leaves the original collection untouched, as `Map::usort()` already did. If you relied on the in-place sort, use the
returned collection:

```php
// Before
$collection->usort($callback);

// After
$collection = $collection->usort($callback);
```

### `merge()` no longer modifies the original sequence

`merge()` used to add the elements of the given sequence to the sequence it was called on, and return that same
sequence. It now returns a new sequence of the same class, and leaves both sequences untouched. If you relied on the
in-place merge, use the returned sequence:

```php
// Before
$collection->merge($otherCollection);

// After
$collection = $collection->merge($otherCollection);
```

When merging maps, numeric keys are now kept, and the values of the given map overwrite the ones with the same key,
as it already happened with non-numeric keys. They used to be renumbered from 0, so no value was overwritten:

```php
$map = new Map(['10' => 'a']);

// Before: [0 => 'a', 1 => 'b']
// After: [10 => 'b']
$map->merge(new Map(['10' => 'b']));
```

`Sequence` defines a new `protected function mergeElements(array $elements, array $otherElements): array` method,
which is final in `Map`. A map defining its own `mergeElements()` method, or a collection defining it with an
incompatible signature, no longer loads. Rename that method.

### `Collection::join()` has been removed

Use `merge()` instead, which keeps the class of the collection and checks that both collections are compatible. To
combine collections of different types into a plain `Collection`, as `join()` did, build it from their elements:

```php
// Before
$joined = $collection->join($otherCollection);

// After, for collections of the same class
$joined = $collection->merge($otherCollection);

// After, for collections of different classes
$joined = new Collection([...$collection, ...$otherCollection]);
```

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
