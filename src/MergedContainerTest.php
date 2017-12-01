<?php
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace ChrisHarrison\MergedContainer;

use ChrisHarrison\MergedContainer\Exceptions\CannotMergeNonArray;
use ChrisHarrison\MergedContainer\Exceptions\NotFoundInContainer;
use PHPUnit\Framework\TestCase;
use ChrisHarrison\MergedContainer\TestClasses\SimpleContainer;

final class MergedContainerTest extends TestCase
{
    public function test_get_returns_values_from_any_container()
    {
        $container1 = new SimpleContainer([
            'id1' => 'value1',
        ]);

        $container2 = new SimpleContainer([
            'id2' => 'value2',
        ]);

        $container3 = new SimpleContainer([
            'id3' => 'value3',
        ]);

        $test = new MergedContainer([$container1, $container2, $container3]);

        $this->assertEquals('value1', $test->get('id1'));
        $this->assertEquals('value2', $test->get('id2'));
        $this->assertEquals('value3', $test->get('id3'));
    }

    public function test_get_returns_values_from_latest_container_on_conflict()
    {
        /* When an id exists in more than one container, the latest added container takes priority */
        $container1 = new SimpleContainer([
            'id1' => 'value1',
            'id2' => 'value2',
        ]);

        $container2 = new SimpleContainer([
            'id2' => 'value2-replaced',
        ]);

        $test = new MergedContainer([$container1, $container2]);

        $this->assertEquals('value2-replaced', $test->get('id2'));
    }

    public function test_get_throws_exception_when_id_not_found()
    {
        $container1 = new SimpleContainer([
            'id1' => 'value1',
        ]);

        $container2 = new SimpleContainer([
            'id2' => 'value2',
        ]);

        $test = new MergedContainer([$container1, $container2]);

        $this->expectException(NotFoundInContainer::class);
        $test->get('id3');
    }

    public function test_has_takes_into_account_all_containers()
    {
        $container1 = new SimpleContainer([
            'id1' => 'value1',
        ]);

        $container2 = new SimpleContainer([
            'id2' => 'value2',
        ]);

        $container3 = new SimpleContainer([
            'id3' => 'value3',
        ]);

        $test = new MergedContainer([$container1, $container2, $container3]);

        $this->assertTrue($test->has('id1'));
        $this->assertTrue($test->has('id2'));
        $this->assertTrue($test->has('id3'));
        $this->assertFalse($test->has('id4'));
    }

    public function test_get_returns_merged_value_when_requested()
    {
        $container1 = new SimpleContainer([
            'merge' => [
                'a' => 1,
                'b' => 2,
            ],
        ]);

        $container2 = new SimpleContainer([
            'merge' => [
                'c' => 3,
                'd' => 4,
            ],
        ]);

        $test = new MergedContainer([$container1, $container2], ['merge']);
        $merged = $test->get('merge');

        $this->assertTrue(array_key_exists('a', $merged));
        $this->assertTrue(array_key_exists('b', $merged));
        $this->assertTrue(array_key_exists('c', $merged));
        $this->assertTrue(array_key_exists('d', $merged));
    }

    public function test_get_returns_later_containers_first_when_merging_values()
    {
        $container1 = new SimpleContainer([
            'merge' => [
                'a' => 1,
                'b' => 2,
            ],
        ]);

        $container2 = new SimpleContainer([
            'merge' => [
                'c' => 3,
                'a' => 100,
            ],
        ]);

        $test = new MergedContainer([$container1, $container2], ['merge']);
        $merged = $test->get('merge');

        $this->assertEquals(100, $merged['a']);
    }

    public function test_non_array_values_are_ignored_when_merging_arrays()
    {
        $container1 = new SimpleContainer([
            'merge' => [
                'a' => 1,
                'b' => 2,
            ],
        ]);

        $container2 = new SimpleContainer([
            'merge' => 'not-an-array',
        ]);

        $container3 = new SimpleContainer([
            'merge' => [
                'a' => 100
            ],
        ]);

        $test = new MergedContainer([$container1, $container2, $container3], ['merge']);
        $merged = $test->get('merge');

        $this->assertEquals(100, $merged['a']);
        $this->assertEquals(2, $merged['b']);
    }
}
