<?php

namespace Tests;

use Database\Seeders\RoleSeeder;
use Illuminate\Database\Eloquent\Collection as DBCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;

abstract class TestCase extends BaseTestCase
{
    /**
     * Returns true if $item is a Collection
     *
     * @param  mixed  $item
     */
    public function assertIsCollection($item)
    {
        $this->assertTrue((bool) ((get_class($item) == Collection::class) || (get_class($item) == DBCollection::class)));
    }

    /**
     * Assets that the number of items in the collection is equal to the provided number
     *
     * @param  $amount  collection should have this many items
     * @return void
     */
    public function assertCollectionCount(int $amount, $collection)
    {
        $this->assertEquals($amount, $collection->count());
    }

    /**
     * Asserts if $string has a substring equal to $contains
     */
    public function assertStringHasSub($string, $contains)
    {
        $this->assertTrue(str_contains($string, $contains), "{$string} should have substring {$contains}");
    }

    /**
     * Asserts if $string does not have a substring equal to $contains
     */
    public function assertStringDoesntHaveSub($string, $contains)
    {
        $this->assertFalse(str_contains($string, $contains), "{$string} shouldn't have substring {$contains}");
    }

    /**
     * Function to compare floats since PHP really sucks at floats
     *
     * @param  float  $a
     * @param  float  $b
     * @return bool
     */
    protected function floatsEqual($a, $b)
    {
        $epsilon = 0.00001;

        return ($a - $b) < $epsilon;
    }

    /**
     * Assertion to check if the given item is of class $class
     *
     * @param  string  $class  the class item should be
     * @param  object  $item  the item to check
     */
    protected function assertIsClass($class, $item)
    {
        $this->assertEquals($class, get_class($item));
    }

    /**
     * Assertion to see if two variables are the same class
     */
    protected function assertSameClass($a, $b)
    {
        $this->assertEquals(get_class($a), get_class($b));
    }

    /**
     * Assertion to ensure model has all provided items in it's provided list of attributes
     *
     * @param  Model  $object  the object to check
     * @param  array  $list  A list of attributes we expect to exist
     */
    protected function assertModelHasAttributes($model, array $list)
    {
        foreach ($list as $item) {
            $this->assertModelHasAttribute($model, $item);
        }
    }

    /**
     * Assertion to ensure model has attribute
     *
     * @param  Model  $object  the object to check
     * @param  string  $name  The name of the attribute we expect to exist
     */
    protected function assertModelHasAttribute($model, $name)
    {
        $msg = get_class($model)." doesn't have attribute named {$name}";
        $this->assertTrue(array_key_exists($name, $model->getAttributes()), $msg);
    }

    /**
     * Adds the business roles to the current test
     *
     * @return void
     */
    protected function roles()
    {
        (new RoleSeeder)->run();
    }
}
