<?php

namespace Emagister\Collections\Tests\Collection;

use Emagister\Collections\Collection\HCollection;
use Emagister\Collections\HomogeneityChecker;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;

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
}
