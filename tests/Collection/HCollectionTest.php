<?php

namespace Emagister\Collections\Tests\Collection;

use Emagister\Collections\Collection\ArrayCollection;
use Emagister\Collections\Collection\BooleanCollection;
use Emagister\Collections\Collection\HCollection;
use Emagister\Collections\Collection\NumericCollection;
use Emagister\Collections\Collection\StringCollection;
use Emagister\Collections\CollectionException;
use Emagister\Collections\Examples\Generics\ConcreteObject;
use Emagister\Collections\Examples\Generics\ConcreteObjectCollection;
use Emagister\Collections\HomogeneityChecker;
use Emagister\Collections\HomogeneityException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;
use stdClass;

class HCollectionTest extends BaseTestCase
{
    #[Test]
    public function head_method_should_return_first_element_of_a_collection(): void
    {
        $collection = new HCollection(HomogeneityChecker::TYPE_NUMERIC, [1, 3, 5, 7, 9]);

        $this->assertEquals(1, $collection->head());
    }

    #[Test]
    public function head_method_should_return_null_on_an_empty_collection(): void
    {
        $collection = new HCollection(HomogeneityChecker::TYPE_NUMERIC);

        $this->assertNull($collection->head());
    }

    #[Test]
    public function tail_method_should_return_empty_collection(): void
    {
        $collection = new HCollection(HomogeneityChecker::TYPE_NUMERIC, [1, 2]);

        $oneElementCollection = $collection->tail();

        $this->assertEquals(1, $oneElementCollection->count());

        $emptyCollection = $oneElementCollection->tail();

        $this->assertTrue($emptyCollection->isEmpty());

        $this->assertNull($emptyCollection->head());
    }

    #[Test]
    public function reduce_method_works_as_expected(): void
    {
        $collection = new HCollection(HomogeneityChecker::TYPE_NUMERIC, [1, 2, 3, 4]);

        $this->assertEquals(10, $collection->reduce(
            function (int $number, $carry): int {
                return $number + $carry;
            },
            0
        ));
    }

    /** @throws CollectionException */
    #[Test]
    public function derived_collections_keep_the_type_of_a_plain_HCollection(): void
    {
        $collection = new HCollection(HomogeneityChecker::TYPE_NUMERIC, [1, 2, 3]);

        $filtered = $collection->filter(fn(int $number) => $number > 1);

        $this->assertInstanceOf(HCollection::class, $filtered);
        $this->assertSame(HomogeneityChecker::TYPE_NUMERIC, $filtered->type());
        $this->assertSame([2, 3], $filtered->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function derived_collections_keep_the_class_of_a_subclass_setting_its_type_in_the_constructor(): void
    {
        $collection = new ConcreteObjectCollection([new ConcreteObject(1, 'one'), new ConcreteObject(2, 'two')]);

        $filtered = $collection->filter(fn(ConcreteObject $object) => $object->id() > 1);

        $this->assertInstanceOf(ConcreteObjectCollection::class, $filtered);
        $this->assertSame(ConcreteObject::class, $filtered->type());
        $this->assertCount(1, $filtered);
        $this->assertInstanceOf(ConcreteObjectCollection::class, $collection->tail());
        $this->assertInstanceOf(ConcreteObjectCollection::class, $collection->clone());
    }

    /** @throws CollectionException */
    #[Test]
    public function derived_collections_keep_the_class_of_a_builtin_typed_collection(): void
    {
        $collection = new StringCollection(['a', 'b', 'c']);

        $this->assertInstanceOf(StringCollection::class, $collection->slice(1));
        $this->assertInstanceOf(StringCollection::class, $collection->usort(fn($a, $b) => strcmp($b, $a)));
        $this->assertInstanceOf(StringCollection::class, $collection->merge(new StringCollection(['d'])));
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_not_modify_the_original_typed_collection(): void
    {
        $collection = new StringCollection(['a', 'b']);

        $merged = $collection->merge(new StringCollection(['c']));

        $this->assertSame(['a', 'b', 'c'], $merged->toArray());
        $this->assertSame(['a', 'b'], $collection->toArray());
    }

    #[Test]
    public function redefined_constructor_without_create_sequence_override_fails_explicitly(): void
    {
        $collection = new class (['a', 'b']) extends HCollection {
            public function __construct(array $elements = [])
            {
                parent::__construct(HomogeneityChecker::TYPE_STRING, $elements);
            }
        };

        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('if its constructor was redefined, override createSequence()');

        $collection->filter(fn() => true);
    }

    /** @throws CollectionException */
    #[Test]
    public function redefined_constructor_still_taking_type_and_elements_keeps_working(): void
    {
        $collection = new class (HomogeneityChecker::TYPE_STRING, ['a', 'b']) extends HCollection {
            public function __construct(string $type, array $elements = [], ?string $label = null)
            {
                parent::__construct($type, $elements);
            }
        };

        $filtered = $collection->filter(fn(string $element) => $element === 'b');

        $this->assertInstanceOf(get_class($collection), $filtered);
        $this->assertSame(['b'], $filtered->toArray());
    }

    public static function builtinTypedCollections(): array
    {
        return [
            'ArrayCollection' => [new ArrayCollection([[1], [2]]), HomogeneityChecker::TYPE_ARRAY],
            'BooleanCollection' => [new BooleanCollection([true, false]), HomogeneityChecker::TYPE_BOOLEAN],
            'NumericCollection' => [new NumericCollection([1, 2]), HomogeneityChecker::TYPE_NUMERIC],
            'StringCollection' => [new StringCollection(['a', 'b']), HomogeneityChecker::TYPE_STRING],
        ];
    }

    #[Test]
    #[DataProvider('builtinTypedCollections')]
    public function derived_collections_of_builtin_typed_collections_keep_class_type_and_homogeneity(
        HCollection $collection,
        string $type
    ): void {
        $tail = $collection->tail();

        $this->assertInstanceOf(get_class($collection), $tail);
        $this->assertSame($type, $tail->type());
        $this->assertCount(1, $tail);

        $this->expectException(HomogeneityException::class);

        $tail->add(new stdClass());
    }

    #[Test]
    public function group_by_builds_groups_of_the_same_class_as_the_collection(): void
    {
        $collection = new StringCollection(['apple', 'avocado', 'banana']);

        $groups = $collection->groupBy(fn(string $fruit) => $fruit[0]);

        $this->assertSame(['a', 'b'], $groups->keys());
        $this->assertInstanceOf(StringCollection::class, $groups->get('a'));
        $this->assertSame(['apple', 'avocado'], $groups->get('a')->toArray());
        $this->assertInstanceOf(StringCollection::class, $groups->get('b'));
        $this->assertSame(['banana'], $groups->get('b')->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_fail_with_a_collection_of_another_type(): void
    {
        $collection = new HCollection(HomogeneityChecker::TYPE_STRING, ['a']);
        $otherCollection = new HCollection(HomogeneityChecker::TYPE_NUMERIC, [1]);

        $this->expectException(HomogeneityException::class);

        $collection->merge($otherCollection);
    }

    #[Test]
    public function constructor_should_fail_with_elements_of_another_type(): void
    {
        $this->expectException(HomogeneityException::class);

        new HCollection(HomogeneityChecker::TYPE_STRING, [1]);
    }

    #[Test]
    public function diff_method_should_fail_with_a_collection_of_another_class(): void
    {
        $collection = new HCollection(HomogeneityChecker::TYPE_STRING, ['a']);

        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('Sequences types are not compatible');

        $collection->diff(new StringCollection(['b']));
    }

    #[Test]
    public function diff_method_should_fail_with_a_collection_of_another_type(): void
    {
        $collection = new HCollection(HomogeneityChecker::TYPE_STRING, ['a']);

        $this->expectException(HomogeneityException::class);

        $collection->diff(new HCollection(HomogeneityChecker::TYPE_NUMERIC, [1]));
    }

    #[Test]
    public function add_method_should_append_an_element_of_the_collection_type(): void
    {
        $collection = new HCollection(HomogeneityChecker::TYPE_NUMERIC, [1]);

        $collection->add(2);

        $this->assertSame([1, 2], $collection->toArray());
    }
}
