<?php
namespace IVIR3aM\ObjectArrayTools\Traits;

/**
 * Class SerializableTrait
 *
 * this trait implements the Serializable interface and help for serialize
 * and unserialize the object
 *
 * @package IVIR3aM\ObjectArrayTools\Traits
 */
trait SerializableTrait
{
    /**
     * serializing the data fo current object
     *
     * @return string serialized data
     */
    public function serialize()
    {
        $list = [];
        foreach ($this as $index => $value) {
            $list[$index] = $value;
        }
        return serialize($list);
    }

    /**
     * unserialize and serialized array and make this object base on that
     *
     * @param string $serialized array data
     */
    public function unserialize($serialized)
    {
        $list = unserialize($serialized);
        if (is_array($list)) {
            foreach ($list as $index => $value) {
                $this[$index] = $value;
            }
        }
    }
}
