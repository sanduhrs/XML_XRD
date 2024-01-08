<?php
require_once 'XML/XRD.php';

use \PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

/**
 * @covers XML_XRD_PropertyAccess
 */
class XML_XRD_PropertyAccessTest extends PHPUnit_Framework_TestCase
{
    public XML_XRD $xrd;

    public function setUp(): void
    {
        $this->xrd = new XML_XRD();
    }
    public function testArrayAccess(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../xrd/properties.xrd');
        $this->assertTrue(isset($this->xrd['name']));
        $this->assertEquals('Stevie', $this->xrd['name']);
        $this->assertEquals('green', $this->xrd['color']);
        $this->assertNull($this->xrd['empty']);
        $this->assertNull($this->xrd['doesnotexist']);
    }

    public function testArrayAccessNull(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../xrd/properties.xrd');
        $this->assertNull($this->xrd['empty']);
        $this->assertNull($this->xrd['doesnotexist']);
    }

    public function testArrayAccessDoesNotExist(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../xrd/properties.xrd');
        $this->assertFalse(isset($this->xrd['doesnotexist']));
        $this->assertNull($this->xrd['doesnotexist']);
    }

    /**
     * @expectedException XML_XRD_LogicException
     */
    public function testArrayAccessSet(): void
    {
        $this->expectException(XML_XRD_LogicException::class);
        $this->expectExceptionMessage('Changing properties not implemented');
        $this->xrd['foo'] = 'bar';
    }

    /**
     * @expectedException XML_XRD_LogicException
     */
    public function testArrayAccessUnset(): void
    {
        $this->expectException(XML_XRD_LogicException::class);
        $this->expectExceptionMessage('Changing properties not implemented');
        unset($this->xrd['foo']);
    }

    public function testGetPropertiesAll(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../xrd/properties.xrd');
        $props = array();
        foreach ($this->xrd->getProperties() as $property) {
            $this->assertInstanceOf('XML_XRD_Element_Property', $property);
            $props[] = $property;
        }
        $this->assertEquals(6, count($props));

        $this->assertEquals('name', $props[0]->type);
        $this->assertEquals('Stevie', $props[0]->value);

        $this->assertEquals('color', $props[2]->type);
        $this->assertEquals('orange', $props[2]->value);
    }

    public function testGetPropertiesType(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../xrd/properties.xrd');
        $props = array();
        foreach ($this->xrd->getProperties('color') as $property) {
            $this->assertInstanceOf('XML_XRD_Element_Property', $property);
            $props[] = $property;
        }
        $this->assertEquals(2, count($props));

        $this->assertEquals('color', $props[0]->type);
        $this->assertEquals('green', $props[0]->value);

        $this->assertEquals('color', $props[1]->type);
        $this->assertEquals('orange', $props[1]->value);
    }

}

?>