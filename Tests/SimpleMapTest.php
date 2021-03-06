<?php

namespace Fwk\Xml;

/**
 * Test class for XmlFile.
 * Generated by PHPUnit on 2012-07-25 at 22:30:05.
 */
class SimpleMapTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var XmlFile
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new XmlFile(__DIR__.'/test.xml');
    }

    /**
     */
    public function testExists() {
        $this->assertTrue($this->object->exists());
    }

    /**
     */
    public function test__toString() {
        $this->assertInternalType('string', $this->object->__toString());
    }

    public function testSimpleNode()
    {
        $map = new Map();
        $map->add(Path::factory('/test/description', 'desc'));
        
        $result = $map->execute($this->object);
        
        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('desc', $result);
        $this->assertEquals('test description', $result['desc']);
    }
    
    public function testProperties()
    {
        $map = new Map();
        $map->add(Path::factory('/test/properties/property', 'props')
                ->loop(true)
                ->attribute('name')
                ->value('value'));
        
        $result = $map->execute($this->object);
        
        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('props', $result);
        $this->assertTrue(is_array($result['props']));
        $this->assertEquals(2, count($result['props']));
        $this->assertArrayHasKey('name', $result['props'][0]);
        $this->assertArrayHasKey('value', $result['props'][0]);
        $this->assertEquals('test_value', $result['props'][0]['value']);
    }
    
    public function testNamedProperties()
    {
        $map = new Map();
        $map->add(Path::factory('/test/properties/property', 'props')
                ->loop(true, '@name'));
        
        $result = $map->execute($this->object);
        
        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('props', $result);
        $this->assertTrue(is_array($result['props']));
        $this->assertEquals(2, count($result['props']));
        $this->assertArrayHasKey('test', $result['props']);
        $this->assertArrayHasKey('test2', $result['props']);
        $this->assertEquals('test_value', $result['props']['test']);
    }
    
    public function testDefaultValue()
    {
        $map = new Map();
        $map->add(Path::factory('/test/test-default', 'default')
                ->setDefault('value'));
        
        $result = $map->execute($this->object);
        
        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('default', $result);
        $this->assertEquals('value', $result['default']);
    }
    
    public function testFilteredValue()
    {
        $map = new Map();
        $map->add(Path::factory('/test/description', 'desc')
                ->setDefault('value')
                ->filter(function($val) { return strrev($val); }));
        
        $result = $map->execute($this->object);
        
        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('desc', $result);
        $this->assertEquals(strrev("test description"), $result['desc']);
    }
}
