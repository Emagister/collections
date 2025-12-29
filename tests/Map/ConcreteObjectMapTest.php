<?php

namespace Emagister\Collections\Tests\Map;

use Emagister\Collections\CollectionException;
use Emagister\Collections\Tests\ConcreteObject;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;

final class ConcreteObjectMapTest extends BaseTestCase
{
    /** @throws CollectionException */
    #[Test]
    public function it_should_infer_map_element_object_type_with_phpdoc(): void
    {
        $map = new ConcreteObjectMap(
            [
                'one' => new ConcreteObject(1, 'First'),
                'two' => new ConcreteObject(2, 'Second')
            ]
        );

        foreach ($map as $object) {
            $this->assertInstanceOf(ConcreteObject::class, $object);
        }
    }
}
