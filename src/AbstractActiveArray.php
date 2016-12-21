<?php
namespace IVIR3aM\ObjectArrayTools;

use IVIR3aM\ObjectArrayTools\Traits\BaseTrait;
use IVIR3aM\ObjectArrayTools\Traits\IteratorTrait;
use IVIR3aM\ObjectArrayTools\Traits\ArrayAccessTrait;

/**
 * Class AbstractActiveArray
 *
 * this is only gathers all traits together and implements interfaces
 * with extending this class you turn your class to an active array with
 * hooks for updating and filtering and sanitizing.
 * this class is abstract because without the hooks this is only a regular array
 *
 * @package IVIR3aM\ObjectArrayTools
 */
abstract class AbstractActiveArray implements \Countable, \Iterator, \ArrayAccess
{
    use BaseTrait, IteratorTrait, ArrayAccessTrait;

    /**
     * AbstractActiveArray constructor.
     *
     * this constructor is only a suggestion for implementing your array object
     * it can replaced with your own class constructor
     *
     * @param array $data
     */
    public function __construct($data = array())
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this[$key] = $value;
            }
        }
    }
}