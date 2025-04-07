<?php

namespace Emagister\Collections\Tests;

use Emagister\Collections\Collection;
use PHPUnit\Framework\TestCase as BaseTestCase;
use stdClass;

class CollectionTest extends BaseTestCase
{
    /** @test */
    public function head_method_should_return_first_element()
    {
        $collection = new Collection([1, 3, 5, 7, 9]);

        $this->assertEquals(1, $collection->head());
    }

    /** @test */
    public function head_method_should_return_null_on_an_empty_collection()
    {
        $collection = new Collection();

        $this->assertNull($collection->head());
    }

    /** @test */
    public function last_method_should_return_the_last_element()
    {
        $numbers = new Collection([1, 2, 3, 4, 5, 6]);

        $this->assertEquals(6, $numbers->last());
    }

    /** @test */
    public function it_should_return_null_from_last_on_an_empty_collection()
    {
        $emptyCollection = new Collection();

        $this->assertNull($emptyCollection->last());
    }

    /** @test */
    public function tail_method_should_return_empty_collection()
    {
        $collection = new Collection([1, 2]);

        $oneElementCollection = $collection->tail();

        $this->assertEquals(1, $oneElementCollection->count());

        $emptyCollection = $oneElementCollection->tail();

        $this->assertTrue($emptyCollection->isEmpty());

        $this->assertNull($emptyCollection->head());
    }

    /** @test */
    public function filter_should_return_a_zero_indexed_collection()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6]);

        $evenNumbers = $collection->filter(function ($number) {
            return $number % 2 === 0;
        });

        $this->assertNotNull($evenNumbers->head());
    }

    /** @test */
    public function it_should_part_a_collection()
    {
        $numbers = new Collection([1, 2, 3, 4, 5, 6]);

        $partition = $numbers->partition(function ($number) {
            return $number % 2 === 0;
        });

        $this->assertInstanceOf(Collection::class, $partition->head());
        $this->assertInstanceOf(Collection::class, $partition->last());

        $this->assertEquals([2, 4, 6], $partition->head()->toArray());
        $this->assertEquals([1, 3, 5], $partition->last()->toArray());
    }

    /** @test */
    public function it_should_calculate_the_difference_of_two_collections()
    {
        $numbers = new Collection([1, 3, 4, 5, 6, 7, 8]);
        $evenNumbers = new Collection([2, 4, 6, 8, 10]);

        $diff = $numbers->diff($evenNumbers);

        $this->assertEquals([1, 3, 5, 7], $diff->toArray());
    }

    /** @test */
    public function it_should_delete_elements_correctly(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        foreach ($collection as $element) {
            $collection->remove($element);
        }

        $this->assertTrue($collection->isEmpty());
    }

    /** @test */
    public function it_should_iterate_correctly_even_if_deleting_elements_while_iterating(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $iteratedElements = [];

        foreach ($collection as $element) {
            $iteratedElements[] = $element;
            $collection->remove($element);
        }

        $this->assertEquals([1, 2, 3, 4, 5], $iteratedElements);
    }

    /** @test */
    public function it_should_perform_a_deep_clone_when_cloning_the_collection(): void
    {
        $element1 = new stdClass();
        $element1->value = 'element 1 original value';

        $element2 = new stdClass();
        $element2->value = 'element 2 original value';

        $collection = new Collection([$element1, $element2]);

        $clonedCollection = $collection->clone();
        $clonedElement1 = $clonedCollection->head();
        $clonedElement2 = $clonedCollection->last();

        $clonedElement1->value = 'cloned element 1 new value';
        $clonedElement2->value = 'cloned element 2 new value';

        $this->assertEquals('element 1 original value', $element1->value);
        $this->assertEquals('element 2 original value', $element2->value);
        $this->assertEquals('cloned element 1 new value', $clonedElement1->value);
        $this->assertEquals('cloned element 2 new value', $clonedElement2->value);
    }

    /** @test */
    public function it_should_clone_a_non_object_collection_successfully(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $clonedCollection = $collection->clone();

        $this->assertInstanceOf(Collection::class, $clonedCollection);
        $this->assertEquals($collection->toArray(), $clonedCollection->toArray());
    }
}
