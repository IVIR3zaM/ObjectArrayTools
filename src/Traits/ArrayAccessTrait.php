<?php
namespace IVIR3aM\ObjectArrayTools\Traits;

/**
 * Class ArrayAccessTrait
 *
 * this trait implements the ArrayAccess interface and help for accessing the object
 * as an array
 *
 * @package IVIR3aM\ObjectArrayTools\Traits
 */
trait ArrayAccessTrait
{
    /**
     * @var int hold the scalar indexes count of the map ($__arrayMap)
     */
    private $__mapIndex = 0;

    /**
     * @var int hold the scalar indexes count of the concrete array ($__concreteData)
     */
    private $__concreteIndex = 0;

    /**
     * this is necessary for ArrayAccess Interface and check the existing of element by offset
     * @param mixed $offset
     * @return bool the existing of element by offset
     */
    public function offsetExists($offset)
    {
        return is_scalar($offset) && array_key_exists($offset, $this->__concreteData);
    }

    /**
     * this is necessary for ArrayAccess Interface and return and element by offset
     * @param $offset
     * @return mixed the element data or null if it not exists
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset) && $this->__FilterHooks($offset, $this->__concreteData[$offset], false)) {
            return $this->__SanitizeHooks($offset, $this->__concreteData[$offset], false);
        }
    }

    /**
     * this is necessary for ArrayAccess Interface and save and element to array by offset
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (!is_scalar($offset) && !is_null($offset)) {
            return;
        }
        if (is_null($offset) || !$this->offsetExists($offset)) {
            $oldData = null;
            $index = $this->__mapIndex++;
            $hook = 'insert';
        } else {
            $oldData = $this->offsetGet($offset);
            $index = array_search($offset, $this->__arrayMap);
            $hook = 'update';
        }
        if (is_null($offset)) {
            $offset = $this->__concreteIndex;
        }

        //filtering the key ($offset) based on http://www.php.net/manual/en/language.types.array.php
        if (is_numeric($offset) || is_bool($offset)) {
            $offset = intval($offset);
        } else {
            $offset = (string) $offset;
        }

        if (!$this->__FilterHooks($offset, $value, true)) {
            return;
        }

        if (is_numeric($offset) && $offset >= $this->__concreteIndex) {
            $this->__concreteIndex = $offset + 1;
        }
        $value = $this->__SanitizeHooks($offset, $value, true);
        $this->__concreteData[$offset] = $value;
        $this->__arrayMap[$index] = $offset;
        $this->__ChangingHooks($offset, $value, $oldData, $hook);
    }

    /**
     * this is necessary for ArrayAccess Interface and delete element by offset and rearrange the map
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $oldData = $this->offsetGet($offset);
            $index = array_search($offset, $this->__arrayMap);

            unset($this->__concreteData[$index]);
            unset($this->__arrayMap[$index]);
            $this->__arrayMap = array_values($this->__arrayMap);

            $this->__mapIndex = count($this->__arrayMap);

            $this->__ChangingHooks($offset, null, $oldData, 'remove');
        }
    }
}