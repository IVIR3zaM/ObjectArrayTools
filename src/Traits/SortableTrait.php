<?php
namespace IVIR3aM\ObjectArrayTools\Traits;

/**
 * Class SortableTrait
 *
 * this trait implements sorting functionality and some other array functionality.
 * there is 3 list of functions that can be called in __cal() of parent class.
 *
 * @package IVIR3aM\ObjectArrayTools\Traits
 * @todo change method for array_walk_recursive(), array_merge_recursive(), array_replace_recursive()
 */
trait SortableTrait
{
    /**
     * @var array list of functions that accepts an array by reference as argument number one
     */
    private $sortableByReferenceMethods = [
        'shuffle',
        'sort',
        'rsort',
        'asort',
        'arsort',
        'ksort',
        'kasort',
        'natsort',
        'natcasesort',
        'usort',
        'uksort',
        'uasort',
        'array_multisort',
        'array_pop',
        'array_push',
        'array_shift',
        'array_splice',
        'array_unshift',
        'array_walk_recursive',
        'array_walk'
    ];

    /**
     * @var array list of functions that accepts an array by value as argument number one
     */
    private $sortableByValueMethods = [
        'array_change_key_case',
        'array_chunk',
        'array_count_values',
        'array_diff_assoc',
        'array_diff_key',
        'array_diff_uassoc',
        'array_diff_ukey',
        'array_diff',
        'array_fill_keys',
        'array_filter',
        'array_flip',
        'array_intersect_assoc',
        'array_intersect_key',
        'array_intersect_uassoc',
        'array_intersect_ukey',
        'array_intersect',
        'array_keys',
        'array_merge',
        'array_merge_recursive',
        'array_pad',
        'array_product',
        'array_rand',
        'array_reduce',
        'array_replace_recursive',
        'array_replace',
        'array_reverse',
        'array_slice',
        'array_sum',
        'array_udiff_assoc',
        'array_udiff_uassoc',
        'array_udiff',
        'array_uintersect_assoc',
        'array_uintersect_uassoc',
        'array_uintersect',
        'array_unique',
        'array_values'
    ];

    /**
     * @var array list of functions that accepts an array by value as argument number two
     */
    private $sortableSpecialMethods = [
        'array_map',
        'array_search'
    ];

    /**
     * this function remap current data array with a new one
     * @param array $list the new data array for current object
     * @return void
     */
    private function sortableResetArray($list)
    {
        $removedKeys = array_keys($this->baseConcreteData);
        foreach ($list as $index => $value) {
            $this[$index] = $value;
            if (($key = array_search($index, $removedKeys)) !== false) {
                unset($removedKeys[$key]);
            }
        }
        unset($list);
        foreach ($removedKeys as $index) {
            unset($this[$index]);
        }
        if (method_exists($this, 'rewind')) {
            $this->rewind();
        }
    }

    /**
     * @param string $function function
     * @return bool is the function in list of by reference methods
     */
    private function sortableIsByReferenceMethod($function)
    {
        return in_array($function, $this->sortableByReferenceMethods);
    }

    /**
     * call a function by reference and send this array filtered data to it as argument one
     * and then resort the data by new array that calculated in the function
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    private function sortableCallByReference($function, $arguments)
    {
        $list = $this->getData();
        $params = array_merge([&$list], $arguments);
        $result = call_user_func_array($function, $params);
        if ($result) {
            $this->sortableResetArray($list);
        }
        return $result;
    }

    /**
     * @param string $function function
     * @return bool is the function in list of by value methods
     */
    private function sortableIsByValueMethod($function)
    {
        return in_array($function, $this->sortableByValueMethods);
    }

    /**
     * call a function by value and send this array filtered data to it as argument one
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    private function sortableCallByValue($function, $arguments)
    {
        $params = array_merge([$this->getData()], $arguments);
        return call_user_func_array($function, $params);
    }

    /**
     * @param string $function function
     * @return bool is the function in list of special methods
     */
    private function sortableIsSpecialMethod($function)
    {
        return in_array($function, $this->sortableSpecialMethods);
    }

    /**
     * call a function by reference and send this array filtered data to it as argument two
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    private function sortableCallSpecial($function, $arguments)
    {
        $params = array_merge(
            array_slice($arguments, 0, 1),
            [$this->getData()],
            array_values(array_slice($arguments, 1))
        );
        return call_user_func_array($function, $params);
    }

    /**
     * calling a function over current data array
     * this function must call inside an __call() magic method
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    private function sortableCall($function, $arguments)
    {
        $function = strtolower(trim($function));
        if ($this->sortableIsByReferenceMethod($function)) {
            return $this->sortableCallByReference($function, $arguments);
        }

        if ($this->sortableIsByValueMethod($function)) {
            return $this->sortableCallByValue($function, $arguments);
        }

        if ($this->sortableIsSpecialMethod($function)) {
            return $this->sortableCallSpecial($function, $arguments);
        }

        throw new \BadMethodCallException(__CLASS__ . '->' . $function);
    }
}
