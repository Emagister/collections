<?php

namespace Emagister\Collections\Tests;

use Emagister\Collections\CollectionException;
use Emagister\Collections\Map;
use Emagister\Collections\Map\StringMap;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;

class MapTest extends BaseTestCase
{
    #[Test]
    public function it_should_return_true_if_at_least_one_element_matches_the_predicate(): void
    {
        $map = new Map([
            'one' => 1,
            'two' => 2,
            'five' => 5,
            'seven' => 7,
            'nine' => 9
        ]);

        $thereAreEvenNumbers = $map->exists(function ($number) {
            return $number % 2 === 0;
        });

        $this->assertTrue($thereAreEvenNumbers);
    }

    #[Test]
    public function it_should_return_true_if_more_than_one_elements_matches_the_predicate(): void
    {
        $collection = new Map([
            'one' => 1,
            'two' => 2,
            'four' => 4,
            'five' => 5,
            'seven' => 7,
            'nine' => 9
        ]);

        $thereAreEvenNumbers = $collection->exists(function ($number) {
            return $number % 2 === 0;
        });

        $this->assertTrue($thereAreEvenNumbers);
    }

    #[Test]
    public function it_should_return_false_if_no_element_matches_the_predicate(): void
    {
        $collection = new Map([
            'one' => 1,
            'five' => 5,
            'seven' => 7,
            'nine' => 9
        ]);

        $thereAreEvenNumbers = $collection->exists(function ($number) {
            return $number % 2 === 0;
        });

        $this->assertFalse($thereAreEvenNumbers);
    }

    #[Test]
    public function is_should_partition_a_map(): void
    {
        $numbers = new Map([
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6
        ]);

        $partition = $numbers->partition(function ($number) {
            return $number % 2 === 0;
        });

        $this->assertInstanceOf(Map::class, $partition->head());
        $this->assertInstanceOf(Map::class, $partition->last());

        $this->assertEquals(['two' => 2, 'four' => 4, 'six' => 6], $partition->head()->toArray());
        $this->assertEquals(['one' => 1, 'three' => 3, 'five' => 5], $partition->last()->toArray());
    }

    #[Test]
    public function it_should_part_a_map(): void
    {
        $numbers = new Map([
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6
        ]);

        $partition = $numbers->partition(function ($number) {
            return $number % 2 === 0;
        });

        $this->assertInstanceOf(Map::class, $partition->head());
        $this->assertInstanceOf(Map::class, $partition->last());

        $this->assertEquals(['two' => 2, 'four' => 4, 'six' => 6], $partition->head()->toArray());
        $this->assertEquals(['one' => 1, 'three' => 3, 'five' => 5], $partition->last()->toArray());
    }

    #[Test]
    public function it_should_calculate_the_difference_of_two_collections(): void
    {
        $numbers = new Map([
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8
        ]);

        $evenNumbers = new Map([
            'two' => 2,
            'four' => 4,
            'six' => 6,
            'eight' => 8,
            'ten' => 10
        ]);

        $diff = $numbers->diff($evenNumbers);

        $this->assertEquals(['one' => 1, 'three' => 3, 'five' => 5, 'seven' => 7], $diff->toArray());
    }

    #[Test]
    public function it_should_delete_elements_correctly(): void
    {
        $map = new Map([
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5
        ]);

        foreach ($map as $element) {
            $map->remove($element);
        }

        $this->assertTrue($map->isEmpty());
    }

    #[Test]
    public function it_should_iterate_correctly_even_if_deleting_elements_while_iterating(): void
    {
        $map = new Map([
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5
        ]);

        $iteratedElements = [];

        foreach ($map as $key => $element) {
            $iteratedElements[$key] = $element;
            $map->remove($element);
        }

        $this->assertEquals(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5], $iteratedElements);
    }

    #[Test]
    public function it_should_keep_keys_when_cloning_a_map(): void
    {
        $stringIndexedMap = new Map([
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5
        ]);

        $clonedStringIndexedMap = $stringIndexedMap->clone();

        $this->assertInstanceOf(Map::class, $clonedStringIndexedMap);
        $this->assertEquals($stringIndexedMap->toArray(), $clonedStringIndexedMap->toArray());

        $integerIndexedMap = new Map([
            5 => 'five',
            2 => 'two',
            4 => 'four'
        ]);

        $clonedIntegerIndexedMap = $integerIndexedMap->clone();

        $this->assertEquals($integerIndexedMap->toArray(), $clonedIntegerIndexedMap->toArray());
    }

    /**
     * @test
     *
     * @throws CollectionException
     */
    public function equals_method_should_return_true_for_maps_with_same_elements_in_different_order(): void
    {
        $map1 = new Map(['1' => 1, '2' => 2]);
        $map2 = new Map(['2' => 2, '1' => 1]);

        $this->assertTrue(
            $map1->equals($map2),
            'Maps with same elements in different order should be equal.'
        );
    }

    /**
     * @test
     *
     * @throws CollectionException
     */
    public function equals_method_should_check_element_types(): void
    {
        $map1 = new Map(['1' => 1, '2' => 2]);
        $map2 = new Map(['1' => '1', '2' => '2']);

        $this->assertFalse(
            $map1->equals($map2),
            'Collections with elements of different types should not be equal.'
        );
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_return_a_map_with_the_elements_of_both_maps(): void
    {
        $map = new Map(['one' => 1, 'two' => 2]);
        $otherMap = new Map(['two' => 22, 'three' => 3]);

        $mergedMap = $map->merge($otherMap);

        $this->assertSame(['one' => 1, 'two' => 22, 'three' => 3], $mergedMap->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_not_modify_the_original_maps(): void
    {
        $map = new Map(['one' => 1, 'two' => 2]);
        $otherMap = new Map(['two' => 22, 'three' => 3]);

        $mergedMap = $map->merge($otherMap);

        $this->assertNotSame($map, $mergedMap);
        $this->assertSame(['one' => 1, 'two' => 2], $map->toArray());
        $this->assertSame(['two' => 22, 'three' => 3], $otherMap->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_keep_numeric_keys(): void
    {
        $map = new Map(['10' => 'a', '20' => 'b']);
        $otherMap = new Map(['30' => 'c']);

        $mergedMap = $map->merge($otherMap);

        $this->assertSame([10 => 'a', 20 => 'b', 30 => 'c'], $mergedMap->toArray());
        $this->assertSame(['10', '20', '30'], $mergedMap->keys());
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_overwrite_duplicate_numeric_keys_with_the_values_of_the_other_map(): void
    {
        $map = new Map(['10' => 'a', '20' => 'b']);
        $otherMap = new Map(['10' => 'z']);

        $mergedMap = $map->merge($otherMap);

        $this->assertSame([10 => 'z', 20 => 'b'], $mergedMap->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_keep_numeric_and_string_keys_together(): void
    {
        $map = new Map(['10' => 'a', 'x' => 'b']);
        $otherMap = new Map(['20' => 'c', '10' => 'z', 'x' => 'y']);

        $mergedMap = $map->merge($otherMap);

        $this->assertSame([10 => 'z', 'x' => 'y', 20 => 'c'], $mergedMap->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_return_the_elements_of_the_other_map_when_merging_into_an_empty_map(): void
    {
        $map = new Map();
        $otherMap = new Map(['10' => 'a', 'x' => 'b']);

        $this->assertSame([10 => 'a', 'x' => 'b'], $map->merge($otherMap)->toArray());
        $this->assertSame([10 => 'a', 'x' => 'b'], $otherMap->merge($map)->toArray());
    }

    #[Test]
    public function merge_method_should_fail_with_a_map_of_another_class(): void
    {
        $map = new Map(['one' => 'a']);

        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('Sequences types are not compatible');

        $map->merge(new StringMap(['two' => 'b']));
    }
}
