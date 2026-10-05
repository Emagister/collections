<?php

namespace Emagister\Collections\Tests;

use ArrayObject;
use Countable;
use Emagister\Collections\HomogeneityChecker;
use Emagister\Collections\HomogeneityException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;
use stdClass;

class HomogeneityCheckerTest extends BaseTestCase
{
    #[Test]
    public function element_type_method_should_return_the_class_of_an_object(): void
    {
        $checker = new HomogeneityChecker(stdClass::class);

        $this->assertSame(stdClass::class, $checker->elementType(new stdClass()));
    }

    #[Test]
    public function element_type_method_should_return_the_type_of_a_non_object(): void
    {
        $checker = new HomogeneityChecker(HomogeneityChecker::TYPE_NUMERIC);

        $this->assertSame('integer', $checker->elementType(1));
    }

    public static function nonNumericElements(): array
    {
        return [
            'boolean' => [true],
            'numeric string' => ['1'],
            'array' => [[1]],
        ];
    }

    #[Test]
    #[DataProvider('nonNumericElements')]
    public function numeric_type_should_reject_non_numeric_elements($element): void
    {
        $checker = new HomogeneityChecker(HomogeneityChecker::TYPE_NUMERIC);

        $this->expectException(HomogeneityException::class);

        $checker->checkElement($element);
    }

    #[Test]
    public function class_type_should_reject_non_object_elements(): void
    {
        $checker = new HomogeneityChecker(stdClass::class);

        $this->expectException(HomogeneityException::class);

        $checker->checkElement(1);
    }

    #[Test]
    public function class_type_should_reject_objects_of_unrelated_classes(): void
    {
        $checker = new HomogeneityChecker(Countable::class);

        $this->expectException(HomogeneityException::class);
        $this->expectExceptionMessage('This sequence can only hold elements of type Countable, stdClass given');

        $checker->checkElement(new stdClass());
    }

    #[Test]
    public function class_type_should_accept_objects_implementing_the_interface(): void
    {
        $checker = new HomogeneityChecker(Countable::class);

        $this->expectNotToPerformAssertions();

        $checker->checkElement(new ArrayObject());
    }

    #[Test]
    public function class_type_should_accept_objects_extending_the_class(): void
    {
        $checker = new HomogeneityChecker(stdClass::class);

        $this->expectNotToPerformAssertions();

        $checker->checkElement(new class () extends stdClass {
        });
    }
}
