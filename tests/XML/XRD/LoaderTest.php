<?php
require_once 'XML/XRD/Loader.php';
require_once 'XML/XRD.php';

use \PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

/**
 * @covers XML_XRD_Loader
 */
class XML_XRD_LoaderTest extends PHPUnit_Framework_TestCase
{
    protected $cleanupList = [];

    public function setUp(): void
    {
        $this->xrd = new XML_XRD();
        $this->loader = new XML_XRD_Loader($this->xrd);
    }

    public function tearDown(): void
    {
        foreach ($this->cleanupList as $k => $file) {
            chmod($file, '0700');
            unlink($file);
            unset($this->cleanupList[$k]);
        }
    }


    public function testLoadFileTypeNull(): void
    {
        $this->loader->loadFile(
            __DIR__ . '/../../xrd/properties.xrd'
        );
        $this->assertEquals('http://example.com/gpburdell', $this->xrd->subject);
    }

    /**
     * @expectedException XML_XRD_Loader_Exception
     * @expectedExceptionMessage No loader for XRD type "foobarbaz"
     */
    public function testLoadFileTypeWrong(): void
    {
        $this->expectException(XML_XRD_Loader_Exception::class);
        $this->expectExceptionMessage('No loader for XRD type "foobarbaz"');
        @$this->loader->loadFile(
            __DIR__ . '/../../xrd/properties.xrd',
            'foobarbaz'
        );
    }

    public function testLoadFileTypeXml(): void
    {
        $this->loader->loadFile(
            __DIR__ . '/../../xrd/properties.xrd',
            'xml'
        );
        $this->assertEquals('http://example.com/gpburdell', $this->xrd->subject);
    }

    public function testLoadStringTypeNull(): void
    {
        $this->loader->loadString(
            '{"subject":"gpburdell@example.org"}'
        );
        $this->assertEquals('gpburdell@example.org', $this->xrd->subject);
    }

    /**
     * @expectedException XML_XRD_Loader_Exception
     * @expectedExceptionMessage No loader for XRD type "foobarbaz"
     */
    public function testLoadStringTypeWrong(): void
    {
        $this->expectException(XML_XRD_Loader_Exception::class);
        $this->expectExceptionMessage('No loader for XRD type "foobarbaz"');
        @$this->loader->loadString(
            '{"subject":"gpburdell@example.org"}',
            'foobarbaz'
        );
    }

    public function testLoadStringJson(): void
    {
        $this->loader->loadString(
            '{"subject":"gpburdell@example.org"}',
            'json'
        );
        $this->assertEquals('gpburdell@example.org', $this->xrd->subject);
    }

    /**
     * @expectedException XML_XRD_Loader_Exception
     * @expectedExceptionMessage Error loading XRD file: File does not exist
     */
    public function testDetectTypeFromFileDoesNotExist(): void
    {
        $this->expectException(XML_XRD_Loader_Exception::class);
        $this->expectExceptionMessage('Error loading XRD file: File does not exist');
        $this->loader->detectTypeFromFile(__DIR__ . '/../doesnotexist');
    }

    /**
     * @expectedException XML_XRD_Loader_Exception
     * @expectedExceptionMessage Cannot open file to determine type
     */
    public function testDetectTypeFromFileCannotOpen(): void
    {
        $this->expectException(XML_XRD_Loader_Exception::class);
        $this->expectExceptionMessage('Cannot open file to determine type');
        $file = tempnam(sys_get_temp_dir(), 'xml_xrd-unittests');
        $this->cleanupList[] = $file;
        chmod($file, '0000');
        @$this->loader->detectTypeFromFile($file);
    }


    /**
     * @expectedException XML_XRD_Loader_Exception
     * @expectedExceptionMessage Detecting file type failed
     */
    public function testDetectTypeFromStringUnknownFormat(): void
    {
        $this->expectException(XML_XRD_Loader_Exception::class);
        $this->expectExceptionMessage('Detecting file type failed');
        $this->loader->detectTypeFromString('asdf');
    }

}
?>