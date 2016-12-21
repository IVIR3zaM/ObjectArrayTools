<?php
namespace IVIR3aM\ObjectArrayTools\Tests;

/**
 * Class FullArrayTest
 * @package IVIR3aM\ObjectArrayTools\Tests
 */
class BasicArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \IVIR3aM\ObjectArrayTools\Tests\ActiveArray
     */
    private $array;

    public function setUp()
    {
        $this->array = new ActiveArray(array('Hello', 'World', 'New' => 'How', 'Are', 'You'));
    }

    public function testCountable()
    {
        $this->assertEquals(5, count($this->array));

        $this->array = new ActiveArray([1]);
        $this->assertEquals(1, count($this->array));
    }

    public function testIterative()
    {
        $this->assertEquals(0, $this->array->key());
        $this->assertEquals('Hello', $this->array->current());

        $this->array->next();
        $this->assertEquals(1, $this->array->key());
        $this->assertEquals('World', $this->array->current());

        $this->array->next();
        $this->array->next();
        $this->array->next();

        $this->assertEquals(3, $this->array->key());
        $this->assertEquals('You', $this->array->current());

        $this->array->next();
        $this->assertEquals(false, $this->array->valid());
        $this->assertEquals(null, $this->array->key());
        $this->assertEquals(null, $this->array->current());

        $this->array->rewind();
        $this->assertEquals(0, $this->array->key());
        $this->assertEquals('Hello', $this->array->current());
    }

    public function testArrayAccess()
    {
        $this->assertEquals('Hello', $this->array[0]);
        $this->assertEquals('World', $this->array[1]);
        $this->assertEquals('How', $this->array['New']);
        $this->assertEquals('Are', $this->array[2]);
        $this->assertEquals('You', $this->array[3]);
        $this->assertEquals(null, $this->array[4]);

        $key = [1];
        $this->array->offsetSet($key, 'NotValid');
        $this->assertEquals(null, $this->array->offsetGet($key));

        $this->array['New'] = 'Who';
        $this->assertEquals('Who', $this->array['New']);
        $this->assertEquals(5, count($this->array));

        $this->array[] = 'Iam';
        $this->assertEquals('Iam', $this->array[4]);
        $this->assertEquals(6, count($this->array));

        $this->array['OK'] = 'Ok!';
        $this->assertEquals('Ok!', $this->array['OK']);
        $this->assertEquals(7, count($this->array));

        unset($this->array[2]);
        $this->assertEquals(6, count($this->array));

        $this->array[] = 'Bye';
        $this->assertEquals('Bye', $this->array[5]);
        $this->assertEquals(7, count($this->array));

        $this->assertEquals(false, isset($this->array['FALSE']));
        $this->assertEquals(true, isset($this->array[0]));
        $this->assertEquals(true, isset($this->array[1]));
    }

    public function testUpdateSignalReceive()
    {
        $this->assertEquals(array(3, 'You'), $this->array->update);

        $this->array[0] = '2';
        $this->assertEquals(array(0, 'Hello'), $this->array->update);
    }

    public function testSanitizingInput()
    {
        $this->array = new ActiveArray(array('test'));
        $this->assertEquals('Test', $this->array[0]);
    }

    public function testFilteringInput()
    {
        $this->array[] = 'FILTER';
        $this->array[] = 'Show';
        $this->assertEquals(6, count($this->array));

        $this->assertEquals('Show', $this->array[4]);
        $this->assertEquals(null, $this->array['FILTER']);
    }

    public function testSanitizingOutput()
    {
        $this->array['UPPER'] = 'Test';
        $this->assertEquals('TEST', $this->array['UPPER']);
    }

    public function testFilteringOutput()
    {
        $this->array['NULL'] = 'NoShow';
        $this->array[] = 'Show';
        $this->assertEquals(7, count($this->array));

        $this->assertEquals('Show', $this->array[4]);
        $this->assertEquals(null, $this->array['NULL']);
    }
}