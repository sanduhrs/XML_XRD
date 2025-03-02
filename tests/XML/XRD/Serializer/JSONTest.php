<?php
require_once 'XML/XRD.php';

use \PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

/**
 * @covers XML_XRD_Serializer_JSON
 */
class XML_XRD_Serializer_JSONTest extends PHPUnit_Framework_TestCase
{
    public function testXrdRfc6415A(): void
    {
        $filePath = __DIR__ . '/../../../';
        $x = new XML_XRD();
        $x->loadFile($filePath . 'xrd/rfc6415-A.xrd');
        $this->assertEquals(
            json_decode(file_get_contents($filePath . 'jrd/rfc6415-A.jrd')),
            json_decode($x->to('json'))
        );
    }

    public function testRemoveEmptyLinksArray(): void
    {
        $x = new XML_XRD();
        $x->subject = 'foo';

        $res = new stdClass();
        $res->subject = 'foo';
        $this->assertEquals(
            $res,
            json_decode($x->to('json'))
        );
    }
}

?>