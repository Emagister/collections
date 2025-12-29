<?php

namespace Emagister\Collections\Tests\Collection;

use Emagister\Collections\CollectionException;
use Emagister\Collections\Tests\ConcreteObject;
use PHPUnit\Framework\TestCase as BaseTestCase;

final class ConcreteObjectCollectionTest extends BaseTestCase
{
    /**
     * @test
     * @throws CollectionException
     */
    public function it_should_infer_collection_element_object_type_with_phpdoc(): void
    {
        $collection = new ConcreteObjectCollection([
            new ConcreteObject(1, 'First'),
        ]);

        foreach ($collection as $object) {
            $this->assertInstanceOf(ConcreteObject::class, $object);
        }
    }
}
