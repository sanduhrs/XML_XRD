<?php
require_once 'XML/XRD.php';
require_once 'XML/XRD/Loader/XML.php';

use \PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

/**
 * @covers XML_XRD_Loader_XML
 */
class XML_XRD_Loader_XMLTest extends PHPUnit_Framework_TestCase
{

    protected XML_XRD $xrd;

    protected XML_XRD_Loader_XML $xl;

    public function setUp(): void
    {
        $this->xrd = new XML_XRD();
        $this->xl = new XML_XRD_Loader_XML($this->xrd);
    }

    /**
     * @expectedException XML_XRD_Loader_Exception
     * @expectedExceptionMessage Error loading XML file: failed to load external entity
     */
    public function testLoadFileDoesNotExist(): void
    {
        $this->expectException(XML_XRD_Loader_Exception::class);
        $this->expectExceptionMessage('Error loading XML file: failed to load external entity');
        $this->xl->loadFile(__DIR__ . '/../doesnotexist');
    }
    /**
     * @expectedException XML_XRD_Loader_Exception
     * @expectedExceptionMessage Error loading XML string: string empty
     */
    public function testLoadStringEmpty(): void
    {
        $this->expectException(XML_XRD_Loader_Exception::class);
        $this->expectExceptionMessage('Error loading XML string: string empty');
        $this->xl->loadString('');
    }

    /**
     * @expectedException XML_XRD_Loader_Exception
     * @expectedExceptionMessage Error loading XML string: Start tag expected
     */
    public function testLoadStringFailBrokenXml(): void
    {
        $this->expectException(XML_XRD_Loader_Exception::class);
        $this->expectExceptionMessage('Error loading XML string: Start tag expected');
        $this->xl->loadString("<?xml");
    }

    /**
     * @expectedException XML_XRD_Loader_Exception
     * @expectedExceptionMessage Wrong document namespace
     */
    public function testLoadXmlWrongNamespace(): void
    {
        $this->expectException(XML_XRD_Loader_Exception::class);
        $this->expectExceptionMessage('Wrong document namespace');
        $xrdstring = <<<XRD
<?xml version="1.0"?>
<XRD xmlns="http://this/is/wrong">
  <Subject>http://example.com/gpburdell</Subject>
</XRD>
XRD;
        $this->xl->loadString($xrdstring);
    }

    /**
     * @expectedException XML_XRD_Loader_Exception
     * @expectedExceptionMessage XML root element is not "XRD"
     */
    public function testLoadXmlWrongRootElement(): void
    {
        $this->expectException(XML_XRD_Loader_Exception::class);
        $this->expectExceptionMessage('XML root element is not "XRD"');
        $xrdstring = <<<XRD
<?xml version="1.0"?>
<FOO xmlns="http://docs.oasis-open.org/ns/xri/xrd-1.0">
  <Subject>http://example.com/gpburdell</Subject>
</FOO>
XRD;
        $this->xl->loadString($xrdstring);
    }

    public function testPropertyExpiresNone(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/properties.xrd');
        $this->assertNull($this->xrd->expires);
    }

    public function testPropertyExpiresTimestampZero(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/xrd-1.0-b1.xrd');
        $this->assertEquals(0, $this->xrd->expires);
    }

    public function testPropertyExpiresTimestamp(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/expires.xrd');
        $this->assertEquals(123456, $this->xrd->expires);
    }

    public function testPropertySubjectNone(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/expires.xrd');
        $this->assertNull($this->xrd->subject);
    }

    public function testPropertySubject(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/xrd-1.0-b1.xrd');
        $this->assertEquals('http://example.com/gpburdell', $this->xrd->subject);
    }

    public function testPropertyAliasNone(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/xrd-1.0-b1.xrd');
        $this->assertEquals(array(), $this->xrd->aliases);
    }

    public function testPropertyAlias(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/xrd-1.0-b2.xrd');
        $this->assertIsArray($this->xrd->aliases);
        $this->assertEquals(2, count($this->xrd->aliases));
        $this->assertEquals('http://people.example.com/gpburdell', $this->xrd->aliases[0]);
        $this->assertEquals('acct:gpburdell@example.com', $this->xrd->aliases[1]);
    }

    public function testPropertyIdNone(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/expires.xrd');
        $this->assertNull($this->xrd->id);
    }

    public function testPropertyId(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/xrd-1.0-b2.xrd');
        $this->assertEquals('foo', $this->xrd->id);
    }

    public function testLoadAlias(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/xrd-1.0-b2.xrd');
        $this->assertIsArray($this->xrd->aliases);
        $this->assertEquals(2, count($this->xrd->aliases));
        $this->assertEquals('http://people.example.com/gpburdell', $this->xrd->aliases[0]);
        $this->assertEquals('acct:gpburdell@example.com', $this->xrd->aliases[1]);
    }

    public function testLoadProperties(): void
    {
        $this->xl->loadFile(__DIR__ . '/../../../xrd/properties.xrd');
        $this->assertEquals('Stevie', $this->xrd['name']);
        $this->assertEquals('green', $this->xrd['color']);
    }
}

?>