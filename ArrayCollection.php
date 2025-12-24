<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/23/25
 *  Time:   15:41
*/


namespace Foamycastle\Utilities;
use Countable;
use Exception;

/**
 * Elements are stored as tuples
 */
class ArrayCollection implements Collection
{
    protected array $data = [];
    protected function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Returns all elements of the collection with the specified key.
     */
    public function get(mixed $key):array
    {
        return array_filter(
            $this->data,
            function($item) use ($key) {
                return $item[0] === $key;
            }
        );
    }

    public function serialize()
    {
        return serialize($this->data);
    }

    public function unserialize(string $data)
    {
        return unserialize($data);
    }

    public function jsonSerialize(): mixed
    {
        return json_encode($this->data);
    }

    /**
     * Checks whether the collection contains an element with the specified key.
     */
    public function has(mixed $key): bool
    {
       return $this->get($key) !== [];
    }

    /**
     * Adds an element to the collection.
     */
    public function put(mixed $key, mixed $value):void
    {
        $this->data[] = [$key, $value];
    }

    /**
     * Returns all elements of the collection.
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Returns the first element of the collection.
     */
    public function first(mixed $key=null): mixed
    {
        if(!$key) return reset($this->data);
        $got = $this->get($key);
        return reset($got);
    }

    /**
     * Returns the last element of the collection.
     */
    public function last(mixed $key=null): mixed
    {
        if(!$key) return end($this->data);
        $got = $this->get($key);
        return end($got);
    }

    /**
     * Counts the number of items in the collection.
     */
    public function count():int
    {
        return count($this->data);
    }

    /**
     * Counts the number of times a given key occurs in the collection.
     */
    public function countKeysOf(mixed $key):int
    {
        return count($this->get($key));
    }

    /**
     * Counts the number of times a given value occurs in the collection.
     */
    public function countValuesOf(mixed $value):int
    {
        return count(array_filter($this->data, fn($item) => $item[1] === $value));
    }

    /**
     * Finds all collection items possessing a given key.
     */
    public function findKeysOf(mixed $value, bool $strict=true):Collection
    {
        return new self(
            array_keys(
                array_filter(
                    $this->data,
                    function($item) use ($value, $strict) {
                        return $strict
                            ? $item[0] === $value
                            : $item[0] == $value;
                    }
                )
            )
        );
    }
    /**
     * Finds all collection items possessing a given value.
     */
    public function findValuesOf(mixed $value, bool $strict=true):Collection
    {
        return new self(
            array_values(
                array_filter(
                    $this->data,
                    function($item) use ($value, $strict) {
                        return $strict
                            ? $item[1] === $value
                            : $item[1] == $value;
                    }
                )
            )
        );
    }

    /**
     * Clears the collection.
     */
    public function clear():void
    {
        $this->data = [];
    }
    /**
     * Checks whether the collection is empty.
     */
    public function isEmpty():bool
    {
        return empty($this->data);
    }

    /**
     * Removes elements from the collection based on the specified key and mode.
     *
     * @param mixed $key The key to be removed from the collection.
     * @param Remove $mode Determines how the removal should be performed:
     *                  - Remove::ALL: Removes all occurrences of the key.
     *                  - Remove::FIRST: Removes the first occurrence of the key.
     *                  - Remove::LAST: Removes the last occurrence of the key.
     * @return Collection The updated collection after the removal operation.
     */
    public function removeByKey(mixed $key, Remove $mode):Collection
    {
        switch($mode){
            case Remove::ALL:
                return new self(array_filter($this->data, fn($item) => $item[0] !== $key));

            case Remove::FIRST:
                $findAll = $this->findKeysOf($key)->first();
                if($findAll) unset($this->data[key($findAll)]);
                return new self($this->data);
            case Remove::LAST:
                $findAll = $this->findKeysOf($key)->last();
                if($findAll) unset($this->data[key($findAll)]);
                return new self($this->data);
        }
        return $this;
    }

    /**
     * Removes elements from the collection based on the specified value and mode.
     *
     * @param mixed $value The value to be removed from the collection.
     * @param Remove $mode Determines how the removal should be performed:
     *                  - Remove::ALL: Removes all occurrences of the value.
     *                  - Remove::FIRST: Removes the first occurrence of the value.
     *                  - Remove::LAST: Removes the last occurrence of the value.
     * @return Collection The updated collection after the removal operation.
     */
    public function removeByValue(mixed $value, Remove $mode):Collection
    {
        switch($mode){
            case Remove::ALL:
                return new self(array_filter($this->data, fn($item) => $item[1] !== $value));
            case Remove::FIRST:
                $findAll = $this->findValuesOf($value)->first();
                if($findAll) unset($this->data[key($findAll)]);
                return new self($this->data);
            case Remove::LAST:
                $findAll = $this->findValuesOf($value)->last();
                if($findAll) unset($this->data[key($findAll)]);
                return new self($this->data);
        }
        return $this;
    }

    /**
     * Returns a new collection containing all elements except those with the specified keys.
     *
     */
    public function allExceptByKeys(array $keys):Collection
    {
        if(!array_is_list($keys)) throw new \InvalidArgumentException('$key argument must be a list');
        $outputArray=[];
        foreach ($this->data as $datum) {
            [$key,$value]=$datum;
            foreach ($keys as $k) {
                if($k===$key) continue;
            }
            $outputArray[]=$datum;
        }
        return new self($outputArray);
    }

    /**
     * Returns a new collection containing all elements except those with the specified values.
     */
    public function allExceptByValues(mixed $values):Collection
    {
        if(!array_is_list($values)) throw new \InvalidArgumentException('$values argument must be a list');
        $outputArray=[];
        foreach ($this->data as $datum) {
            [$key,$value]=$datum;
            foreach ($values as $v) {
                if($v===$value) continue;
            }
            $outputArray[]=$datum;
        }
        return new self($outputArray);
    }
    public function consolidate(mixed $key):Collection
    {
        $outputArray=[$key]=null;
        foreach ($this->findKeysOf($key)->all() as $item) {
            [$k,$v]=$item;
            $outputArray[$key]=$v;
        }
        return new self($outputArray);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->put($offset,$value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $findAll = $this->findKeysOf($offset)->first();
        if($findAll) unset($this->data[key($findAll)]);
    }

    public function __serialize(): array
    {
        return $this->data;
    }

    public function __unserialize(array $data): void
    {
        $this->data=$data;
    }

    public function current(): mixed
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): mixed
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return is_array($this->current()) && count($this->current()) == 2;
    }

    public function rewind(): void
    {
        reset($this->data);
    }


    public static function New(array $data = []):Collection
    {
        $new=new self();
        if(count($data)==0) return $new;
        foreach ($data as $datum) {
            if(is_array($datum)){
                if(count($datum)==0)
                    continue;
                if(count($datum)==1)
                    $new->put($datum[0],null);
                if(count($datum)>=2)
                    $key = array_shift($datum);
                    if(count($datum)==1)
                        $new->put($key,$datum[0]);
                    else{
                        $new->put($key,$datum);
                    }
            }elseif(is_scalar($datum)){
                $new->put($datum,null);
            }else throw new Exception(
                'ArrayCollection constructor expects an array of tuples or scalars'
            );
        }
        return $new;
    }
}