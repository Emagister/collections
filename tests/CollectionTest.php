<?php

namespace Emagister\Collections\Tests;

use Emagister\Collections\Collection;
use Emagister\Collections\Collection\StringCollection;
use Emagister\Collections\CollectionException;
use Emagister\Collections\Tests\Fixtures\SampleEnum;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;
use stdClass;
use TypeError;

class CollectionTest extends BaseTestCase
{
    #[Test]
    public function head_method_should_return_first_element(): void
    {
        $collection = new Collection([1, 3, 5, 7, 9]);

        $this->assertEquals(1, $collection->head());
    }

    #[Test]
    public function head_method_should_return_null_on_an_empty_collection(): void
    {
        $collection = new Collection();

        $this->assertNull($collection->head());
    }

    #[Test]
    public function last_method_should_return_the_last_element(): void
    {
        $numbers = new Collection([1, 2, 3, 4, 5, 6]);

        $this->assertEquals(6, $numbers->last());
    }

    #[Test]
    public function it_should_return_null_from_last_on_an_empty_collection(): void
    {
        $emptyCollection = new Collection();

        $this->assertNull($emptyCollection->last());
    }

    #[Test]
    public function tail_method_should_return_empty_collection(): void
    {
        $collection = new Collection([1, 2]);

        $oneElementCollection = $collection->tail();

        $this->assertEquals(1, $oneElementCollection->count());

        $emptyCollection = $oneElementCollection->tail();

        $this->assertTrue($emptyCollection->isEmpty());

        $this->assertNull($emptyCollection->head());
    }

    #[Test]
    public function filter_should_return_a_zero_indexed_collection(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6]);

        $evenNumbers = $collection->filter(function ($number) {
            return $number % 2 === 0;
        });

        $this->assertNotNull($evenNumbers->head());
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_return_a_collection_with_the_elements_of_both_collections(): void
    {
        $collection = new Collection(['a', 'b', 'c']);
        $otherCollection = new Collection(['d', 'e', 'f']);

        $mergedCollection = $collection->merge($otherCollection);

        $this->assertSame(['a', 'b', 'c', 'd', 'e', 'f'], $mergedCollection->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_append_the_elements_of_an_empty_or_non_empty_collection(): void
    {
        $collection = new Collection(['a', 'b']);
        $emptyCollection = new Collection();

        $this->assertSame(['a', 'b'], $collection->merge($emptyCollection)->toArray());
        $this->assertSame(['a', 'b'], $emptyCollection->merge($collection)->toArray());
        $this->assertSame(['a', 'b', 'a', 'b'], $collection->merge($collection)->toArray());
    }

    #[Test]
    public function merge_method_should_fail_with_a_collection_of_another_class(): void
    {
        $collection = new Collection(['a']);

        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('Sequences types are not compatible');

        $collection->merge(new StringCollection(['b']));
    }

    /** @throws CollectionException */
    #[Test]
    public function merge_method_should_not_modify_the_original_collections(): void
    {
        $collection = new Collection(['a', 'b', 'c']);
        $otherCollection = new Collection(['d', 'e', 'f']);

        $mergedCollection = $collection->merge($otherCollection);

        $this->assertNotSame($collection, $mergedCollection);
        $this->assertSame(['a', 'b', 'c'], $collection->toArray());
        $this->assertSame(['d', 'e', 'f'], $otherCollection->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function it_should_part_a_collection(): void
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

    /** @throws CollectionException */
    #[Test]
    public function partition_method_should_not_lose_data_for_elements_with_different_types(): void
    {
        $collection = new Collection([1, '1']);

        $partitioned = $collection->partition(fn($x) => is_int($x));

        $intCollection = $partitioned->head();
        $noIntCollection = $partitioned->last();

        $this->assertEquals(1, $intCollection->count(), 'Integer collection should have 1 element.');
        $this->assertEquals(1, $noIntCollection->count(), 'No-integer collection should have 1 element.');

        $this->assertTrue($intCollection->contains(1), 'Integer collection should contain integer value');
        $this->assertTrue($noIntCollection->contains('1'), 'No-integer collection should contain string value');
    }

    #[Test]
    public function contains_method_should_use_value_equality_for_objects(): void
    {
        $object = new stdClass();
        $object->name = 'Advanced PHP';

        $sameValueDifferentInstance = clone $object;

        $collection = new Collection([$object]);

        $this->assertTrue(
            $collection->contains($sameValueDifferentInstance),
            'Collection should contain an object that is equal by value, even if it is a different instance.'
        );
    }

    #[Test]
    public function contains_method_should_return_false_for_objects_with_different_property_values(): void
    {
        $object = new stdClass();
        $object->name = 'Advanced PHP';

        $differentObject = new stdClass();
        $differentObject->name = 'Advanced JavaScript';

        $collection = new Collection([$object]);

        $this->assertFalse(
            $collection->contains($differentObject),
            'Collection should not contain an object whose properties differ from any element.'
        );
    }

    /** @throws CollectionException */
    #[Test]
    public function it_should_calculate_the_difference_of_two_collections(): void
    {
        $numbers = new Collection([1, 3, 4, 5, 6, 7, 8]);
        $evenNumbers = new Collection([2, 4, 6, 8, 10]);

        $diff = $numbers->diff($evenNumbers);

        $this->assertEquals([1, 3, 5, 7], $diff->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function diff_method_should_work_properly_with_elements_with_different_types(): void
    {
        $numbers = new Collection([1, 3, 4, 5, 6, 7, 8]);
        $evenNumbers = new Collection(['2', 4, '6', 8, '10']);

        $diff = $numbers->diff($evenNumbers);

        $this->assertEquals([1, 3, 5, 6, 7], $diff->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function diff_method_should_treat_a_cloned_collection_as_having_no_differences(): void
    {
        $element = new stdClass();
        $element->name = 'Collections Library';

        $collection = new Collection([$element]);
        $clonedCollection = $collection->clone();

        $diff = $collection->diff($clonedCollection);

        $this->assertTrue(
            $diff->isEmpty(),
            'Diffing a collection against its own clone should yield no differences.'
        );
    }

    #[Test]
    public function remove_method_should_only_remove_the_strictly_equal_element(): void
    {
        $collection = new Collection([1, '1']);

        $collection->remove('1');

        $this->assertEquals([1], $collection->toArray());
    }

    #[Test]
    public function remove_method_should_not_remove_anything_when_element_is_not_present(): void
    {
        $collection = new Collection([1, 2, 3]);

        $removed = $collection->remove(4);

        $this->assertFalse($removed, 'Removing an absent element should return false.');
        $this->assertEquals([1, 2, 3], $collection->toArray());
    }

    #[Test]
    public function remove_method_should_use_value_equality_for_objects(): void
    {
        $object = new stdClass();
        $object->name = 'Advanced PHP';

        $sameValueDifferentInstance = clone $object;

        $collection = new Collection([$object]);

        $removed = $collection->remove($sameValueDifferentInstance);

        $this->assertTrue(
            $removed,
            'Collection should remove an object that is equal by value, even if it is a different instance.'
        );
        $this->assertTrue($collection->isEmpty());
    }

    #[Test]
    public function remove_method_should_not_remove_objects_with_different_property_values(): void
    {
        $object = new stdClass();
        $object->name = 'Advanced PHP';

        $differentObject = new stdClass();
        $differentObject->name = 'Advanced JavaScript';

        $collection = new Collection([$object]);

        $removed = $collection->remove($differentObject);

        $this->assertFalse(
            $removed,
            'Collection should not remove an object whose properties differ from any element.'
        );
        $this->assertEquals([$object], $collection->toArray());
    }

    #[Test]
    public function it_should_delete_elements_correctly(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        foreach ($collection as $element) {
            $collection->remove($element);
        }

        $this->assertTrue($collection->isEmpty());
    }

    #[Test]
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

    /** @throws CollectionException */
    #[Test]
    public function clone_method_should_keep_the_same_enum_cases(): void
    {
        $collection = new Collection([SampleEnum::First, SampleEnum::Second]);

        $clonedCollection = $collection->clone();

        $this->assertNotSame($collection, $clonedCollection);
        $this->assertSame([SampleEnum::First, SampleEnum::Second], $clonedCollection->toArray());
    }

    #[Test]
    public function cloning_a_collection_of_enum_cases_should_keep_the_same_enum_cases(): void
    {
        $collection = new Collection([SampleEnum::First, SampleEnum::Second]);

        $clonedCollection = clone $collection;

        $this->assertSame([SampleEnum::First, SampleEnum::Second], $clonedCollection->toArray());
    }

    #[Test]
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

        $collectionCollection = new Collection([$collection]);
        $clonedCollectionCollection = $collectionCollection->clone();

        $clonedElement1 = $clonedCollectionCollection->head()->head();
        $clonedElement2 = $clonedCollectionCollection->head()->last();

        $clonedElement1->value = 'cloned element 1 new value';
        $clonedElement2->value = 'cloned element 2 new value';

        $this->assertEquals('element 1 original value', $element1->value);
        $this->assertEquals('element 2 original value', $element2->value);
        $this->assertEquals('cloned element 1 new value', $clonedElement1->value);
        $this->assertEquals('cloned element 2 new value', $clonedElement2->value);
    }

    #[Test]
    public function it_should_clone_a_non_object_collection_successfully(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $clonedCollection = $collection->clone();

        $this->assertInstanceOf(Collection::class, $clonedCollection);
        $this->assertEquals($collection->toArray(), $clonedCollection->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function equals_method_should_return_true_for_collections_with_same_elements_in_different_order(): void
    {
        $collection1 = new Collection([1, 2]);
        $collection2 = new Collection([2, 1]);

        $this->assertTrue(
            $collection1->equals($collection2),
            'Collections with same elements in different order should be equal.'
        );
    }

    /** @throws CollectionException */
    #[Test]
    public function equals_method_should_check_element_types(): void
    {
        $collection1 = new Collection([1, 2]);
        $collection2 = new Collection(['1', '2']);

        $this->assertFalse(
            $collection1->equals($collection2),
            'Collections with elements of different types should not be equal.'
        );
    }

    #[Test]
    public function derived_collections_fail_explicitly_when_the_constructor_cannot_take_the_elements(): void
    {
        $collection = new class ('label') extends Collection {
            public function __construct(string $label)
            {
                parent::__construct([$label]);
            }
        };

        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('if its constructor was redefined, override createSequence()');

        $collection->slice(0);
    }

    #[Test]
    public function derived_collections_work_when_the_redefined_constructor_can_take_the_elements(): void
    {
        $collection = new class ([1, 2, 3]) extends Collection {
            public function __construct(iterable $elements = [], int ...$flags)
            {
                parent::__construct([...$elements]);
            }
        };

        $sliced = $collection->slice(1);

        $this->assertInstanceOf(get_class($collection), $sliced);
        $this->assertSame([2, 3], $sliced->toArray());
    }

    #[Test]
    public function the_error_raised_when_the_constructor_cannot_take_the_elements_is_kept_as_previous(): void
    {
        $collection = new class ('label') extends Collection {
            public function __construct(string $label)
            {
                parent::__construct([$label]);
            }
        };

        try {
            $collection->slice(0);
            $this->fail('A CollectionException was expected');
        } catch (CollectionException $exception) {
            $this->assertInstanceOf(TypeError::class, $exception->getPrevious());
        }
    }

    /** @throws CollectionException */
    #[Test]
    public function usort_method_should_not_modify_the_original_collection(): void
    {
        $collection = new Collection([3, 1, 2]);

        $sorted = $collection->usort(fn($a, $b) => $a <=> $b);

        $this->assertSame([1, 2, 3], $sorted->toArray());
        $this->assertSame([3, 1, 2], $collection->toArray());
    }

    #[Test]
    public function add_method_should_append_the_element(): void
    {
        $collection = new Collection([1]);

        $collection->add(2);

        $this->assertSame([1, 2], $collection->toArray());
    }

    #[Test]
    public function remove_method_should_reset_the_element_keys(): void
    {
        $collection = new Collection(['a', 'b', 'c']);

        $collection->remove('a');

        $this->assertSame(['b', 'c'], $collection->toArray());
    }

    #[Test]
    public function each_method_should_call_the_callback_for_every_element(): void
    {
        $collection = new Collection([1, 2, 3]);
        $visitedElements = [];

        $collection->each(function ($element) use (&$visitedElements): void {
            $visitedElements[] = $element;
        });

        $this->assertSame([1, 2, 3], $visitedElements);
    }

    #[Test]
    public function contains_method_should_not_loosely_compare_an_object_with_a_scalar(): void
    {
        $stringable = new class () {
            public function __toString(): string
            {
                return 'a';
            }
        };

        $collection = new Collection([$stringable]);

        $this->assertFalse($collection->contains('a'));
    }

    /** @throws CollectionException */
    #[Test]
    public function contains_with_closure_method_should_use_the_callback_to_compare_elements(): void
    {
        $collection = new Collection([1, 2, 3]);
        $sameNumber = fn($element, $sequenceElement) => $element === $sequenceElement;

        $this->assertTrue($collection->containsWithClosure(2, $sameNumber));
        $this->assertFalse($collection->containsWithClosure(4, $sameNumber));
    }

    /** @throws CollectionException */
    #[Test]
    public function equals_method_should_return_false_for_collections_with_a_different_number_of_elements(): void
    {
        $collection = new Collection([1, 2]);

        $this->assertFalse($collection->equals(new Collection([1, 2, 3])));
    }

    /** @throws CollectionException */
    #[Test]
    public function equals_with_closure_method_should_use_the_callback_to_compare_elements(): void
    {
        $collection = new Collection([1, 2]);
        $sameNumber = fn($element, $sequenceElement) => $element == $sequenceElement;

        $this->assertTrue($collection->equalsWithClosure(new Collection(['2', '1']), $sameNumber));
        $this->assertFalse($collection->equalsWithClosure(new Collection([1, 3]), $sameNumber));
        $this->assertFalse($collection->equalsWithClosure(new Collection([1, 2, 2]), $sameNumber));
    }

    #[Test]
    public function diff_method_should_fail_with_a_collection_of_another_class(): void
    {
        $collection = new Collection(['a']);

        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('Sequences types are not compatible');

        $collection->diff(new StringCollection(['b']));
    }

    /** @throws CollectionException */
    #[Test]
    public function diff_with_closure_method_should_keep_the_elements_not_present_in_the_other_collection(): void
    {
        $collection = new Collection([1, 2, 3]);

        $diff = $collection->diffWithClosure(new Collection([2]), fn($a, $b) => $a === $b);

        $this->assertSame([1, 3], $diff->toArray());
    }

    #[Test]
    public function diff_with_closure_method_should_fail_with_a_collection_of_another_class(): void
    {
        $collection = new Collection(['a']);

        $this->expectException(CollectionException::class);

        $collection->diffWithClosure(new StringCollection(['b']), fn($a, $b) => $a === $b);
    }

    /** @throws CollectionException */
    #[Test]
    public function intersect_with_closure_method_should_keep_the_elements_present_in_the_other_collection(): void
    {
        $collection = new Collection([1, 2, 3]);

        $intersection = $collection->intersectWithClosure(new Collection([2, 4]), fn($a, $b) => $a === $b);

        $this->assertSame([2], $intersection->toArray());
    }

    #[Test]
    public function intersect_with_closure_method_should_fail_with_a_collection_of_another_class(): void
    {
        $collection = new Collection(['a']);

        $this->expectException(CollectionException::class);

        $collection->intersectWithClosure(new StringCollection(['a']), fn($a, $b) => $a === $b);
    }

    #[Test]
    public function map_method_should_return_a_collection_with_the_transformed_elements(): void
    {
        $collection = new Collection([1, 2, 3]);

        $doubled = $collection->map(fn($number) => $number * 2);

        $this->assertInstanceOf(Collection::class, $doubled);
        $this->assertSame([2, 4, 6], $doubled->toArray());
    }

    #[Test]
    public function find_method_should_return_the_first_element_satisfying_the_callback(): void
    {
        $collection = new Collection([1, 2, 3, 4]);

        $this->assertSame(2, $collection->find(fn($number) => $number % 2 === 0));
        $this->assertNull($collection->find(fn($number) => $number > 4));
    }

    #[Test]
    public function find_not_method_should_return_the_first_element_not_satisfying_the_callback(): void
    {
        $collection = new Collection([2, 4, 5, 7]);

        $this->assertSame(5, $collection->findNot(fn($number) => $number % 2 === 0));
        $this->assertNull($collection->findNot(fn($number) => $number > 0));
    }

    #[Test]
    public function for_all_method_should_check_that_every_element_satisfies_the_callback(): void
    {
        $collection = new Collection([2, 4, 6]);

        $this->assertTrue($collection->forAll(fn($number) => $number % 2 === 0));
        $this->assertFalse($collection->forAll(fn($number) => $number < 6));
    }

    #[Test]
    public function remove_with_closure_method_should_remove_the_elements_satisfying_the_callback(): void
    {
        $collection = new Collection([1, 2, 3, 4]);

        $collection->removeWithClosure(fn($number) => $number % 2 === 0);

        $this->assertSame([1, 3], array_values($collection->toArray()));
    }

    #[Test]
    public function count_with_closure_method_should_count_the_elements_satisfying_the_callback(): void
    {
        $collection = new Collection([1, 2, 3, 4, 6]);

        $this->assertSame(3, $collection->countWithClosure(fn($number) => $number % 2 === 0));
    }

    #[Test]
    public function split_method_should_return_a_collection_of_chunks_of_the_given_length(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        $chunks = $collection->split(2);

        $this->assertContainsOnlyInstancesOf(Collection::class, $chunks);
        $this->assertSame(
            [[1, 2], [3, 4], [5]],
            $chunks->map(fn(Collection $chunk) => $chunk->toArray())->toArray()
        );
    }

    #[Test]
    public function random_element_method_should_return_an_element_of_the_collection(): void
    {
        $this->assertSame('a', (new Collection(['a']))->randomElement());
        $this->assertContains((new Collection([1, 2, 3]))->randomElement(), [1, 2, 3]);
    }

    #[Test]
    public function shuffle_method_should_change_the_order_but_keep_the_elements(): void
    {
        $elements = range(1, 20);
        $collection = new Collection($elements);

        $collection->shuffle();

        $this->assertNotSame($elements, $collection->toArray());
        $this->assertEqualsCanonicalizing($elements, $collection->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function subclasses_can_override_create_sequence_and_delegate_to_the_parent(): void
    {
        $collection = new class ([1, 2, 3]) extends Collection {
            public int $createdSequences = 0;

            protected function createSequence(array $elements): static
            {
                $this->createdSequences++;

                return parent::createSequence($elements);
            }
        };

        $filtered = $collection->filter(fn($number) => $number > 1);

        $this->assertSame(1, $collection->createdSequences);
        $this->assertInstanceOf(get_class($collection), $filtered);
        $this->assertSame([2, 3], $filtered->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function subclasses_can_override_merge_elements_and_delegate_to_the_parent(): void
    {
        $collection = new class ([1, 2]) extends Collection {
            protected function mergeElements(array $elements, array $otherElements): array
            {
                return array_unique(parent::mergeElements($elements, $otherElements));
            }
        };

        $merged = $collection->merge(new (get_class($collection))([2, 3]));

        $this->assertSame([1, 2, 3], $merged->toArray());
    }

    /** @throws CollectionException */
    #[Test]
    public function group_by_should_convert_the_discriminators_to_strings(): void
    {
        $collection = new Collection([1.5, 1.7, 1.5]);

        $groups = $collection->groupBy(fn(float $number) => $number);

        $this->assertSame(['1.5', '1.7'], $groups->keys());
        $this->assertSame([1.5, 1.5], $groups->get('1.5')->toArray());
        $this->assertSame([1.7], $groups->get('1.7')->toArray());
    }
}
