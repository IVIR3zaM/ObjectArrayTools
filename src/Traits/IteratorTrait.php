<?php
namespace IVIR3aM\ObjectArrayTools\Traits;

/**
 * Class IteratorTrait
 *
 * this trait implements the Iterator SeekableIterator and help for iterate over
 * the object in foreach loops
 *
 * @package IVIR3aM\ObjectArrayTools\Traits
 */
trait IteratorTrait
{
    /**
     * @var int hold the current position of iteration
     */
    private $iteratorIterationPosition = 0;

    /**
     * this is necessary for Iterator Interface and rewind the position of iteration
     * @return void
     */
    public function rewind()
    {
        $this->iteratorIterationPosition = 0;
    }

    /**
     * this is necessary for SeekableIterator Interface and move
     * the position of the cursor
     * @param int $position
     */
    public function seek($position)
    {
        $this->iteratorIterationPosition = intval($position);
    }

    /**
     * this is necessary for Iterator Interface and return current element
     * @return mixed current element or null
     */
    public function current()
    {
        if ($this->valid()) {
            $key = $this->baseArrayMap[$this->iteratorIterationPosition];
            $value = $this->baseConcreteData[$key];
            return $this->internalFilterHooks($key, $value, 'output') ?
                $this->internalSanitizeHooks($key, $value, 'output') : null;
        }
    }

    /**
     * this is necessary for Iterator Interface and return current key
     * @return mixed current key or null
     */
    public function key()
    {
        if ($this->valid()) {
            return $this->baseArrayMap[$this->iteratorIterationPosition];
        }
    }

    /**
     * this is necessary for Iterator Interface and move current cursor forward
     * @return void
     */
    public function next()
    {
        $this->iteratorIterationPosition++;
    }

    /**
     * this is necessary for Iterator Interface and check the current cursor position is valid or not
     * @return bool is current position of cursor valid (opposite of reached the end of array)?
     */
    public function valid()
    {
        return isset($this->baseArrayMap[$this->iteratorIterationPosition]);
    }
}
