<?php
require_once 'XML/XRD/Loader.php';
require_once 'XML/XRD.php';

use \PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

/**
 * @covers XML_XRD_Serializer
 */
class XML_XRD_SerializerTest extends PHPUnit_Framework_TestCase
{
    protected array $cleanupList = [];

    protected XML_XRD $xrd;

    protected XML_XRD_Serializer $serializer;

    public function setUp(): void
    {
        $this->xrd = new XML_XRD();
        $this->serializer = new XML_XRD_Serializer($this->xrd);
    }

    public function testToJson(): void
    {
        $this->xrd->subject = 'foo@example.org';
        $json = $this->serializer->to('json');
        $this->assertIsString($json);
        $this->assertStringContainsString('foo@example.org', $json);
    }

    /**
     * @expectedException XML_XRD_Serializer_Exception
     * @expectedExceptionMessage No serializer for type "batty"
     */
    public function testToUnsupported(): void
    {
        $this->expectException(XML_XRD_Serializer_Exception::class);
        $this->expectExceptionMessage('No serializer for type "batty"');
        $this->xrd->subject = 'foo@example.org';
        @$this->serializer->to('batty');
    }
}
?>