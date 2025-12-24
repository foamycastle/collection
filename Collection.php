<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/23/25
 *  Time:   15:45
*/


namespace Foamycastle\Utilities;

interface Collection extends \ArrayAccess, \Countable,\Serializable,\JsonSerializable, \Traversable, \Iterator
{
    public function all(): array;
    public function get(mixed $key);
    public function has(mixed $key):bool;
    public function put(mixed $key, $value);

    public function first(mixed $key):mixed;
    public function last(mixed $key):mixed;
    public function count():int;
    public function isEmpty():bool;
    public function removeByKey(mixed $key, Remove $mode):Collection;
    public function removeByValue(mixed $value, Remove $mode):Collection;
    public function countKeysOf(mixed $key):int;
    public function countValuesOf(mixed $value):int;
    public function allExceptByKeys(array $keys):Collection;
    public function allExceptByValues(array $values):Collection;
    public function findKeysOf(mixed $value, bool $strict=true):Collection;
    public function findValuesOf(mixed $value, bool $strict=true):Collection;



}