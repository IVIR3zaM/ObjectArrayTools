<?php
namespace IVIR3aM\ObjectArrayTools\Traits;

/**
 * Class BaseTrait
 *
 * this is the base logic and an implementation for Countable interface. the logic is
 * save an untouched array in $baseConcreteData and then map the keys in $baseArrayMap
 * this will help for Iterator implementation and Sorting functionality.
 * also this trait have the hooks logic for filtering and sanitizing input and output
 * (two underscores is only for preventing of conflicts)
 *
 * @example: [2 => 'Test', 'Key' => 'Value'] will results:
 * $baseConcreteData = [2 => 'Test', 'Key' => 'Value']
 * $baseArrayMap = [0 => 2, 1 => 'Key']
 * @package IVIR3aM\ObjectArrayTools\Traits
 */
trait BaseTrait
{
    /**
     * @var array $baseConcreteData holding concrete array
     */
    private $baseConcreteData = array();

    /**
     * @var array $baseArrayMap hold scalar indexes pairs for concrete indexes and also for sorting purposes
     */
    private $baseArrayMap = array();

    /**
     * this is necessary for Countable Interface
     * @return int the number of elements of this object
     */
    public function count()
    {
        return count($this->baseArrayMap);
    }

    /**
     * this function have the base logic of sanitizing an array element base on
     * both of key and value and then return the sanitized value
     * @param mixed $key the key of the element
     * @param mixed $value the value of the element
     * @param string $type which input or output are we sanitizing
     * @return mixed sanitized or untouched $value of the element
     */
    private function internalSanitizeHooks($key, $value, $type = 'input')
    {
        $hook = trim(strtolower($type)) == 'input' ? 'sanitizeInputHook' : 'sanitizeOutputHook';
        return method_exists($this, $hook) ? $this->$hook($key,
            $value) : $value;
    }

    /**
     * this function have th base logic of filtering input and output base on
     * both of key and value
     * @param mixed $key the key of the element
     * @param mixed $value the value of the element
     * @param string $type which input or output are we sanitizing
     * @return bool is current element valid for input or output
     */
    private function internalFilterHooks($key, $value, $type = 'input')
    {
        $hook = '';
        switch (trim(strtolower($type))) {
            case 'output':
                $hook = 'filterOutputHook';
                break;
            case 'remove':
                $hook = 'filterRemoveHook';
                break;
            case 'input':
                $hook = 'filterInputHook';
                break;
        }
        if ($hook && method_exists($this, $hook)) {
            $result = $this->$hook($key, $value);
            if ($result !== true && $result !== null) {
                return false;
            }
        }
        return true;
    }

    /**
     * check for existing of changing element hook method in current object and
     * send the key, the value and the old value of to it. tis helps you to have
     * an active array
     * @param mixed $key the key of the element
     * @param mixed $value the value of the element
     * @param mixed $oldValue the old value of the element
     * @param string $type the type of changing (insert|update|remove)
     * @return void
     */
    private function internalChangingHooks($key, $value, $oldValue = null, $type = 'insert')
    {
        switch (trim(strtolower($type))) {
            case 'update':
                $hook = 'updateHook';
                if (method_exists($this, $hook)) {
                    $this->$hook($key, $value, $oldValue);
                }
                break;
            case 'remove':
                $hook = 'removeHook';
                if (method_exists($this, $hook)) {
                    $this->$hook($key, $value);
                }
                break;
            case 'insert':
                $hook = 'insertHook';
                if (method_exists($this, $hook)) {
                    $this->$hook($key, $value);
                }
                break;
        }
    }
}
