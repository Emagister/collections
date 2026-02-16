<?php
/**
 * This example demonstrates the implementation of collections and maps with concrete objects
 * using generics through PHPDoc (since Generics do not yet exist in PHP). You should review the
 * implementation of the ConcreteObject, ConcreteObjectCollection, and ConcreteObjectMap classes
 * to understand how generics are used in this context.
 */

include_once __DIR__ . '/../vendor/autoload.php';

use Emagister\Collections\Examples\Generics\ConcreteObject;
use Emagister\Collections\Examples\Generics\ConcreteObjectCollection;
use Emagister\Collections\Examples\Generics\ConcreteObjectMap;

$collection = new ConcreteObjectCollection([
    new ConcreteObject(1, 'One'),
    new ConcreteObject(2, 'Two'),
    new ConcreteObject(3, 'Three')
]);

foreach ($collection as $key => $object) {
    echo sprintf('ConcreteObject Key: %s => ', $key);
    echo sprintf("ObjectId: %d; ObjectName: %s\n", $object->id(), $object->name());
}

$map = new ConcreteObjectMap(
    [
        'one' => new ConcreteObject(10, 'Ten'),
        'two' => new ConcreteObject(20, 'Twenty'),
        'three' => new ConcreteObject(30, 'Thirty')
    ]
);

$index = 0;
foreach ($map as $key => $object) {
    echo sprintf('ConcreteObject Key: %s => ', $key);
    echo sprintf("ObjectId: %d; ObjectName: %s\n", $object->id(), $object->name());
}
