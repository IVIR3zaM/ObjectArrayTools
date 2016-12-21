<?php
namespace IVIR3aM\ObjectArrayTools\Traits;

/**
 * Class IteratorTrait
 *
 * this trait implements the Iterator interface and help for iterate over
 * the object in foreach loops
 *
 * @package IVIR3aM\ObjectArrayTools\Traits
 */
trait IteratorTrait
{
    /**
     * @var int hold the current position of iteration
     */
    private $__iterationPosition = 0;

    /**
     * this is necessary for Iterator Interface and rewind the position of iteration
     * @return void
     */
    public function rewind()
    {
        $this->__iterationPosition = 0;
    }

    /**
     * this is necessary for Iterator Interface and return current element
     * @return mixed current element or null
     */
    public function current()
    {
        if ($this->valid()) {
            $key = $this->__arrayMap[$this->__iterationPosition];
            $value = $this->__concreteData[$key];
            return $this->__FilterHooks($key, $value, false) ? $this->__SanitizeHooks($key, $value, false) : null;
        }
    }

    /**
     * this is necessary for Iterator Interface and return current key
     * @return mixed current key or null
     */
    public function key()
    {
        if ($this->valid()) {
            return $this->__arrayMap[$this->__iterationPosition];
        }
    }

    /**
     * this is necessary for Iterator Interface and move current cursor forward
     * @return void
     */
    public function next()
    {
        $this->__iterationPosition++;
    }

    /**
     * this is necessary for Iterator Interface and check the current cursor position is valid or not
     * @return bool is current position of cursor valid (opposite of reached the end of array)?
     */
    public function valid()
    {
        return isset($this->__arrayMap[$this->__iterationPosition]);
    }
}