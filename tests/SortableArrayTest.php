<?php
namespace IVIR3aM\ObjectArrayTools\Tests;

/**
 * Class SortableArrayTest
 * @package IVIR3aM\ObjectArrayTools\Tests
 */
class SortableArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \IVIR3aM\ObjectArrayTools\Tests\ActiveArray
     */
    private $array;

    /**
     * @var array
     */
    private $data = ['Hello', 'World', 'New' => 'How', 'Are', 'You'];

    public function setUp()
    {
        $this->array = new UntouchedArray($this->data);
    }

    public function testSort()
    {
        $this->array->sort();
        $this->assertEquals('Are', $this->array->current());

        $this->array->rsort();
        $this->assertEquals('You', $this->array->current());
    }

    public function testArrayFunctions()
    {
        $array = $this->array->array_change_key_case(CASE_LOWER);
        $this->assertEquals('How', $array['new']);

        $this->assertEquals(array_count_values($this->data), $this->array->array_count_values());
    }

    public function testSpecialFunctions()
    {
        $this->data[] = 0;
        $this->array[] = 0;
        $this->assertEquals(array_search('New', $this->data), $this->array->array_search('New'));
        $this->assertEquals(array_search('New', $this->data, true), $this->array->array_search('New', true));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testInvalidFunction()
    {
        $this->array->strtolower('New');
    }
}
