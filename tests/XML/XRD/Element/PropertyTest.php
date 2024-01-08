<?php
require_once 'XML/XRD/Element/Property.php';

use \PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

class XML_XRD_Element_PropertyTest extends PHPUnit_Framework_TestCase
{
    public function test__constructParams(): void
    {
        $prop = new XML_XRD_Element_Property(
            'http://spec.example.net/created/1.0', '1970-01-01'
        );
        $this->assertEquals('http://spec.example.net/created/1.0', $prop->type);
        $this->assertEquals('1970-01-01', $prop->value);
    }

    public function test__constructParamsNullValue(): void
    {
        $prop = new XML_XRD_Element_Property(
            'http://spec.example.net/created/1.0'
        );
        $this->assertEquals('http://spec.example.net/created/1.0', $prop->type);
        $this->assertNull($prop->value);
    }

}
?>