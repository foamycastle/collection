<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/23/25
 *  Time:   16:09
*/


namespace Foamycastle\Utilities;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ArrayCollectionTest extends TestCase
{
    private static array $data = [];

    function setUp(): void
    {
        self::$data = [
            [new \stdClass(),'value1'],
            [[5,5,5],'value2'],
            [true,'value3'],
            [true,'value88'],
            [59,'value88'],
            ['key1','value4'],
            [5,'value5'],
        ];
    }


    public function testAll()
    {
        $arrayCollection = new ArrayCollection(self::$data);
        $this->assertCount(7, $arrayCollection->all());
    }

    public function testFirst()
    {
        $arrayCollection = new ArrayCollection(self::$data);
        [$object,$value] = $arrayCollection->first();
        $this->assertInstanceOf(\stdClass::class, $object);
        $this->assertEquals('value1', $value);
        [$boolean,$value] = $arrayCollection->first(true);
        $this->assertTrue($boolean);
        $this->assertEquals('value3', $value);
    }

    public function testLast()
    {
        $arrayCollection = new ArrayCollection(self::$data);
        [$object,$value] = $arrayCollection->last();
        $this->assertEquals(5, $object);
        $this->assertEquals('value5', $value);
        [$key,$value] = $arrayCollection->last('key1');
        $this->assertEquals('key1', $key);
        $this->assertEquals('value4', $value);
    }
    public function testPut()
    {
        $arrayCollection = new ArrayCollection();
        $arrayCollection->put('key1','value1');
        $this->assertCount(1, $arrayCollection->all());
    }
    public function testCountKeysOf()
    {
        $arrayCollection = new ArrayCollection(self::$data);
        $this->assertEquals(2, $arrayCollection->countKeysOf(true));
    }
    public function testCountValuesOf()
    {
        $arrayCollection = new ArrayCollection(self::$data);
        $this->assertEquals(2, $arrayCollection->countValuesOf('value88'));
    }
}
