<?php
namespace IVIR3aM\ObjectArrayTools;

use IVIR3aM\ObjectArrayTools\Traits\BaseTrait;
use IVIR3aM\ObjectArrayTools\Traits\IteratorTrait;
use IVIR3aM\ObjectArrayTools\Traits\ArrayAccessTrait;
use IVIR3aM\ObjectArrayTools\Traits\SerializableTrait;
use IVIR3aM\ObjectArrayTools\Traits\SortableTrait;

/**
 * Class AbstractActiveArray
 *
 * this is only gathers all traits together and implements interfaces
 * with extending this class you turn your class to an active array with
 * hooks for updating and filtering and sanitizing.
 * this class is abstract because without the hooks this is only a regular array
 *
 * @package IVIR3aM\ObjectArrayTools
 * @method bool sort(int $sort_flags = SORT_REGULAR)
 * @method bool rsort(int $sort_flags = SORT_REGULAR)
 * @method bool asort(int $sort_flags = SORT_REGULAR)
 * @method bool arsort(int $sort_flags = SORT_REGULAR)
 * @method bool ksort(int $sort_flags = SORT_REGULAR)
 * @method bool kasort(int $sort_flags = SORT_REGULAR)
 * @method bool natsort()
 * @method bool natcasesort()
 * @method bool usort(callable $value_compare_func)
 * @method bool uksort(callable $value_compare_func)
 * @method bool uasort(callable $value_compare_func)
 * @method bool array_multisort(mixed $array1_sort_order = SORT_ASC, mixed $array1_sort_flags = SORT_REGULAR)
 * @method array|false array_change_key_case(int $case = CASE_LOWER)
 * @method array array_chunk(int $size, bool $preserve_keys = false)
 * @method array array_count_values()
 * @method array array_diff_assoc(array $array2)
 * @method array array_diff_key(array $array2)
 * @method array array_diff_uassoc(array $array2, callable $key_compare_func)
 * @method array array_diff_ukey(array $array2, callable $key_compare_func)
 * @method array array_diff(array $array2)
 * @method array array_fill_keys(mixed $value)
 * @method array array_filter(callable $callback, int $flag = 0)
 * @method array array_flip()
 * @method array array_intersect_assoc(array $array2)
 * @method array array_intersect_key(array $array2)
 * @method array array_intersect_uassoc(array $array2, callable $key_compare_func)
 * @method array array_intersect_ukey(array $array2, callable $key_compare_func)
 * @method array array_intersect(array $array2)
 * @method array array_keys(mixed $search_value = null, bool $strict = false)
 * @method array array_map(callable $callback)
 * @method array array_merge(array $array2)
 * @method array array_merge_recursive(array $array2)
 * @method array array_pad(int $size, mixed $value)
 * @method mixed array_pop()
 * @method number array_product()
 * @method number array_push(mixed $value1)
 * @method mixed array_rand(int $num = 1)
 * @method mixed array_reduce(callable $callback, mixed $initial = null)
 * @method mixed array_replace_recursive(array $array2)
 * @method mixed array_replace(array $array2)
 * @method array array_reverse(bool $preserve_keys = false)
 * @method array array_search(mixed $needle, bool $strict = false)
 * @method mixed array_shift()
 * @method array array_slice(int $offset, int $length = null, bool $preserve_keys = false)
 * @method array array_splice(int $offset, int $length = null, mixed $replacement = array())
 * @method number array_sum()
 * @method array array_udiff_assoc(array $array2, callable $value_compare_func)
 * @method array array_udiff_uassoc(array $array2, callable $value_compare_func, callable $key_compare_func)
 * @method array array_udiff(array $array2, callable $value_compare_func)
 * @method array array_uintersect_assoc(array $array2, callable $value_compare_func)
 * @method array array_uintersect_uassoc(array $array2, callable $value_compare_func, callable $key_compare_func)
 * @method array array_uintersect(array $array2, callable $value_compare_func)
 * @method array array_unique(int $sort_flags = SORT_STRING)
 * @method int array_unshift(mixed $value1) ++++
 * @method array array_values()
 * @method bool array_walk_recursive(callable $callback, mixed $userdata = null)
 * @method bool array_walk(callable $callback, mixed $userdata = null)
 */
abstract class AbstractActiveArray implements \Countable, \SeekableIterator, \ArrayAccess, \Serializable
{
    use BaseTrait, IteratorTrait, ArrayAccessTrait, SerializableTrait, SortableTrait;

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

    /**
     * implementing array functionality functions
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->sortableCall($name, $arguments);
    }
}
