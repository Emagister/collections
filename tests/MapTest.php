<?php

namespace Emagister\Collections\Tests;

use Emagister\Collections\Collection;
use Emagister\Collections\Map;
use PHPUnit\Framework\TestCase as BaseTestCase;

class MapTest extends BaseTestCase
{
    /** @test */
    public function it_should_return_true_if_at_least_one_element_matches_the_predicate()
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

    /** @test */
    public function it_should_return_true_if_more_than_one_elements_matches_the_predicate()
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

    /** @test */
    public function it_should_return_false_if_no_element_matches_the_predicate()
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

    /** @test */
    public function is_should_partition_a_map()
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

    /** @test */
    public function it_should_part_a_map()
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

        $this->assertEquals(['two' => 2, 'four' =>  4, 'six' => 6], $partition->head()->toArray());
        $this->assertEquals(['one' => 1, 'three' => 3, 'five' => 5], $partition->last()->toArray());
    }

    /** @test */
    public function it_should_calculate_the_difference_of_two_collections()
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

    /** @test */
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

    /** @test */
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
}
