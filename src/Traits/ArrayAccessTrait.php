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
     * @var int hold the scalar indexes count of the map ($baseArrayMap)
     */
    private $arrayAccessMapIndex = 0;

    /**
     * @var int hold the scalar indexes count of the concrete array ($baseConcreteData)
     */
    private $arrayAccessConcreteIndex = 0;

    /**
     * this is necessary for ArrayAccess Interface and check the existing of element by offset
     * @param mixed $offset
     * @return bool the existing of element by offset
     */
    public function offsetExists($offset)
    {
        return is_scalar($offset) && array_key_exists($offset, $this->baseConcreteData);
    }

    /**
     * this is necessary for ArrayAccess Interface and return and element by offset
     * @param $offset
     * @return mixed the element data or null if it not exists
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset) &&
            $this->internalFilterHooks($offset, $this->baseConcreteData[$offset], 'output')
        ) {
            return $this->internalSanitizeHooks($offset, $this->baseConcreteData[$offset], 'output');
        }
    }

    private function offsetIsNew($offset)
    {
        return is_null($offset) || !$this->offsetExists($offset);
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

        $isNew = $this->offsetIsNew($offset);
        $oldData = $isNew ? null : $this->offsetGet($offset);
        $index = $isNew ? $this->arrayAccessMapIndex++ : array_search($offset, $this->baseArrayMap);
        $hook = $isNew ? 'insert' : 'update';

        if (is_null($offset)) {
            $offset = $this->arrayAccessConcreteIndex;
        }

        //filtering the key ($offset) based on http://www.php.net/manual/en/language.types.array.php
        $offset = is_numeric($offset) || is_bool($offset) ? intval($offset) : (string)$offset;

        if (!$this->internalFilterHooks($offset, $value, 'input')) {
            return;
        }

        if (is_numeric($offset) && $offset >= $this->arrayAccessConcreteIndex) {
            $this->arrayAccessConcreteIndex = $offset + 1;
        }
        $value = $this->internalSanitizeHooks($offset, $value, 'input');
        $this->baseConcreteData[$offset] = $value;
        $this->baseArrayMap[$index] = $offset;
        $this->internalChangingHooks($offset, $value, $oldData, $hook);
    }

    /**
     * this is necessary for ArrayAccess Interface and delete element by offset and rearrange the map
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset) && $this->internalFilterHooks($offset, null, 'remove')) {
            $oldData = $this->offsetGet($offset);

            unset($this->baseConcreteData[$offset]);
            if (($index = array_search($offset, $this->baseArrayMap, true)) !== false) {
                unset($this->baseArrayMap[$index]);
            }
            $this->baseArrayMap = array_values($this->baseArrayMap);

            $this->arrayAccessMapIndex = count($this->baseArrayMap);

            $this->internalChangingHooks($offset, null, $oldData, 'remove');
        }
    }
}
