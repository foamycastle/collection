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
            ['multiple','tank','city']
        ];
    }


    public function testAll()
    {
        $arrayCollection = ArrayCollection::New(self::$data);
        $this->assertCount(8, $arrayCollection->all());
    }

    public function testFirst()
    {
        $arrayCollection = ArrayCollection::New(self::$data);
        [$object,$value] = $arrayCollection->first();
        $this->assertInstanceOf(\stdClass::class, $object);
        $this->assertEquals('value1', $value);
        [$boolean,$value] = $arrayCollection->first(true);
        $this->assertTrue($boolean);
        $this->assertEquals('value3', $value);
        [$key, $valueArray] = $arrayCollection->first('multiple');
        $this->assertEquals('multiple', $key);
        $this->assertEquals(['tank','city'], $valueArray);
    }

    public function testLast()
    {
        $arrayCollection = ArrayCollection::New(self::$data);
        [$object,$value] = $arrayCollection->last();
        $this->assertEquals('multiple', $object);
        $this->assertisArray($value);
    }
    public function testPut()
    {
        $arrayCollection = ArrayCollection::New();
        $arrayCollection->put('key1','value1');
        $this->assertCount(1, $arrayCollection->all());
    }
    public function testCountKeysOf()
    {
        $arrayCollection = ArrayCollection::New(self::$data);
        $this->assertEquals(2, $arrayCollection->countKeysOf(true));
    }
    public function testCountValuesOf()
    {
        $arrayCollection = ArrayCollection::New(self::$data);
        $this->assertEquals(2, $arrayCollection->countValuesOf('value88'));
    }
    public function testFindKeysOf()
    {
        $arrayCollection = ArrayCollection::New(self::$data);
        $this->assertCount(2, $arrayCollection->findKeysOf(true));
    }
    public function testFindValuesOf()
    {
        $arrayCollection = ArrayCollection::New(self::$data);
        $this->assertCount(2, $arrayCollection->findValuesOf('value88'));
    }
}
