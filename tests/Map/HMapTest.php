<?php

namespace Emagister\Collections\Tests\Map;

use Closure;
use Emagister\Collections\CollectionException;
use Emagister\Collections\Examples\Generics\ConcreteObject;
use Emagister\Collections\Examples\Generics\ConcreteObjectMap;
use Emagister\Collections\HomogeneityChecker;
use Emagister\Collections\HomogeneityException;
use Emagister\Collections\Map\ArrayMap;
use Emagister\Collections\Map\BooleanMap;
use Emagister\Collections\Map\HMap;
use Emagister\Collections\Map\NumericMap;
use Emagister\Collections\Map\StringMap;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;
use stdClass;

class HMapTest extends BaseTestCase
{
    /** @throws CollectionException */
    #[Test]
    public function head_method_should_return_first_element_of_a_collection(): void
    {
        $collection = new HMap(
            HomogeneityChecker::TYPE_NUMERIC,
            [
                'one' => 1,
                'three' => 3,
                'five' => 5,
                'seven' => 7,
                'nine' => 9,
            ]
        );

        $this->assertEquals(1, $collection->head());
    }

    #[Test]
    public function head_method_should_return_null_on_an_empty_collection(): void
    {
        $collection = new HMap(HomogeneityChecker::TYPE_NUMERIC);

        $this->assertNull($collection->head());
    }

    /** @throws CollectionException */
    #[Test]
    public function tail_method_should_return_empty_collection(): void
    {
        $collection = new HMap(HomogeneityChecker::TYPE_NUMERIC, ['one' => 1, 'two' => 2]);

        $oneElementCollection = $collection->tail();

        $this->assertEquals(1, $oneElementCollection->count());

        $emptyCollection = $oneElementCollection->tail();

        $this->assertTrue($emptyCollection->isEmpty());

        $this->assertNull($emptyCollection->head());
    }

    /** @throws CollectionException */
    #[Test]
    public function derived_maps_keep_the_class_of_a_subclass_setting_its_type_in_the_constructor(): void
    {
        $map = new ConcreteObjectMap(
            [new ConcreteObject(1, 'one'), new ConcreteObject(2, 'two')],
            fn(ConcreteObject $object) => $object->name()
        );

        $filtered = $map->filterKeys(fn(string $key) => $key === 'two');

        $this->assertInstanceOf(ConcreteObjectMap::class, $filtered);
        $this->assertSame(ConcreteObject::class, $filtered->type());
        $this->assertSame(['two'], $filtered->keys());
        $this->assertInstanceOf(ConcreteObjectMap::class, $map->getByKeys('one'));
    }

    /** @throws CollectionException */
    #[Test]
    public function sort_alphabetically_keeps_the_class_of_a_string_map_subclass(): void
    {
        $map = new class (['b' => 'beta', 'a' => 'alpha']) extends StringMap {
        };

        $sorted = $map->sortAlphabetically();

        $this->assertInstanceOf(get_class($map), $sorted);
        $this->assertSame(['a' => 'alpha', 'b' => 'beta'], $sorted->toArray());
    }

    #[Test]
    public function redefined_constructor_without_create_sequence_override_fails_explicitly(): void
    {
        $map = new class (['a' => 'alpha']) extends HMap {
            public function __construct(array $elements = [], ?Closure $elementKeyClosure = null)
            {
                parent::__construct(HomogeneityChecker::TYPE_STRING, $elements, $elementKeyClosure);
            }
        };

        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('if its constructor was redefined, override createSequence()');

        $map->filterKeys(fn() => true);
    }

    public static function builtinTypedMaps(): array
    {
        return [
            'ArrayMap' => [new ArrayMap(['a' => [1], 'b' => [2]]), HomogeneityChecker::TYPE_ARRAY],
            'BooleanMap' => [new BooleanMap(['a' => true, 'b' => false]), HomogeneityChecker::TYPE_BOOLEAN],
            'NumericMap' => [new NumericMap(['a' => 1, 'b' => 2]), HomogeneityChecker::TYPE_NUMERIC],
            'StringMap' => [new StringMap(['a' => 'alpha', 'b' => 'beta']), HomogeneityChecker::TYPE_STRING],
        ];
    }

    #[Test]
    #[DataProvider('builtinTypedMaps')]
    public function derived_maps_of_builtin_typed_maps_keep_class_type_and_homogeneity(HMap $map, string $type): void
    {
        $filtered = $map->filterKeys(fn(string $key) => $key === 'b');

        $this->assertInstanceOf(get_class($map), $filtered);
        $this->assertSame($type, $filtered->type());
        $this->assertSame(['b'], $filtered->keys());

        $this->expectException(HomogeneityException::class);

        $filtered->add('c', new stdClass());
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_keep_the_class_and_numeric_keys_of_a_typed_map(): void
    {
        $map = new StringMap(['10' => 'alpha', 'x' => 'beta']);
        $otherMap = new StringMap(['20' => 'gamma', '10' => 'delta']);

        $mergedMap = $map->merge($otherMap);

        $this->assertInstanceOf(StringMap::class, $mergedMap);
        $this->assertSame([10 => 'delta', 'x' => 'beta', 20 => 'gamma'], $mergedMap->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_fail_with_a_map_of_another_type(): void
    {
        $map = new HMap(HomogeneityChecker::TYPE_STRING, ['a' => 'alpha']);
        $otherMap = new HMap(HomogeneityChecker::TYPE_NUMERIC, ['b' => 1]);

        $this->expectException(HomogeneityException::class);

        $map->merge($otherMap);
    }

    #[Test]
    public function constructor_should_fail_with_elements_of_another_type(): void
    {
        $this->expectException(HomogeneityException::class);

        new HMap(HomogeneityChecker::TYPE_STRING, ['one' => 1]);
    }

    #[Test]
    public function diff_method_should_fail_with_a_map_of_another_class(): void
    {
        $map = new HMap(HomogeneityChecker::TYPE_STRING, ['a' => 'alpha']);

        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('Sequences types are not compatible');

        $map->diff(new StringMap(['b' => 'beta']));
    }

    #[Test]
    public function diff_method_should_fail_with_a_map_of_another_type(): void
    {
        $map = new HMap(HomogeneityChecker::TYPE_STRING, ['a' => 'alpha']);

        $this->expectException(HomogeneityException::class);

        $map->diff(new HMap(HomogeneityChecker::TYPE_NUMERIC, ['b' => 1]));
    }

    /** @throws CollectionException */
    #[Test]
    public function sort_alphabetically_should_sort_in_descending_order(): void
    {
        $map = new StringMap(['a' => 'alpha', 'c' => 'gamma', 'b' => 'beta']);

        $sorted = $map->sortAlphabetically(StringMap::ORDER_DESC);

        $this->assertSame(['c' => 'gamma', 'b' => 'beta', 'a' => 'alpha'], $sorted->toArray());
    }
}
