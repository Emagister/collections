<?php

namespace Emagister\Collections\Tests;

use Emagister\Collections\HomogeneityChecker;
use Emagister\Collections\Map\HMap;
use PHPUnit\Framework\TestCase as BaseTestCase;

class HMapTest extends BaseTestCase
{
    /** @test */
    public function head_method_should_return_first_element_of_a_collection()
    {
        $collection = new HMap(
            HomogeneityChecker::TYPE_NUMERIC, [
                'one' => 1,
                'three' => 3,
                'five' => 5,
                'seven' => 7,
                'nine' => 9
            ]);

        $this->assertEquals(1, $collection->head());
    }

    /** @test */
    public function head_method_should_return_null_on_an_empty_collection()
    {
        $collection = new HMap(HomogeneityChecker::TYPE_NUMERIC);

        $this->assertNull($collection->head());
    }

    /** @test */
    public function tail_method_should_return_empty_collection()
    {
        $collection = new HMap(HomogeneityChecker::TYPE_NUMERIC, ['one' => 1, 'two' => 2]);

        $oneElementCollection = $collection->tail();

        $this->assertEquals(1, $oneElementCollection->count());

        $emptyCollection = $oneElementCollection->tail();

        $this->assertTrue($emptyCollection->isEmpty());

        $this->assertNull($emptyCollection->head());
    }
}
