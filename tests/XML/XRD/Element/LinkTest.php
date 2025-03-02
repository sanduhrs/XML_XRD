<?php
require_once 'XML/XRD.php';

use \PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

/**
 * @covers XML_XRD_Element_Link
 */
class XML_XRD_Element_LinkTest extends PHPUnit_Framework_TestCase
{
    public XML_XRD $xrd;
    public XML_XRD_Element_Link $link;

    public function setUp(): void
    {
        $this->xrd = new XML_XRD();
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/xrd-1.0-b1.xrd');
        $this->link = $this->xrd->get('http://spec.example.net/photo/1.0');
        $this->assertInstanceOf('XML_XRD_Element_Link', $this->link);
    }

    public function test__constructParamHref(): void
    {
        $link = new XML_XRD_Element_Link(
            'http://spec.example.net/photo/1.0',
            'http://photos.example.com/gpburdell.jpg',
            'image/jpeg'
        );
        $this->assertEquals('http://spec.example.net/photo/1.0', $link->rel);
        $this->assertEquals('http://photos.example.com/gpburdell.jpg', $link->href);
        $this->assertEquals('image/jpeg', $link->type);
        $this->assertNull($link->template);
    }

    public function test__constructParamHrefTemplate(): void
    {
        $link = new XML_XRD_Element_Link(
            'lrdd',
            'http://example.org/webfinger/{uri}',
            'application/xrd+xml',
            true
        );
        $this->assertEquals('lrdd', $link->rel);
        $this->assertNull($link->href);
        $this->assertEquals('application/xrd+xml', $link->type);
        $this->assertEquals('http://example.org/webfinger/{uri}', $link->template);
    }



    public function testPropertyRel(): void
    {
        $this->assertEquals('http://spec.example.net/photo/1.0', $this->link->rel);
    }

    public function testPropertyType(): void
    {
        $this->assertEquals('image/jpeg', $this->link->type);
    }

    public function testPropertyHref(): void
    {
        $this->assertEquals('http://photos.example.com/gpburdell.jpg', $this->link->href);
    }

    public function testPropertyTemplate(): void
    {
        $this->xrd->loadFile(__DIR__ . '/../../../xrd/link-template.xrd');
        $this->link = $this->xrd->get('title');
        $this->assertEquals('http://photos.example.com/{uri}.jpg', $this->link->template);
    }

    public function testPropertyTemplateNone(): void
    {
        $this->assertNull($this->link->template);
    }

    public function testPropertyTitles(): void
    {
        $this->assertEquals(
            array('en' => 'User Photo', 'de' => 'Benutzerfoto'),
            $this->link->titles
        );
    }

    public function testGetTitleNoParam(): void
    {
        $this->assertEquals('User Photo', $this->link->getTitle(), 'First title returned');
    }

    public function testGetTitle(): void
    {
        $this->assertEquals('Benutzerfoto', $this->link->getTitle('de'));
    }

    public function testGetTitleNoTitles(): void
    {
        $this->assertNull($this->xrd->get('http://spec.example.net/auth/1.0')->getTitle());
    }

    public function testGetTitleLangNotFound(): void
    {
        $this->assertEquals(
            'User Photo', $this->link->getTitle('fr'),
            'First language returned when not found and none without language'
        );
    }

    public function testGetTitleLangNotFoundFallbackNoLang(): void
    {
        $xrd = new XML_XRD();
        $xrd->loadFile(__DIR__ . '/../../../xrd/link-title.xrd');
        $link = $xrd->get('name');
        $this->assertEquals(
            'Stevie', $link->getTitle('fr'),
            'First title without language when not found'
        );
    }


    public function testArrayAccess(): void
    {
        $xrd = new XML_XRD();
        $xrd->loadFile(__DIR__ . '/../../../xrd/properties.xrd');
        $link = $xrd->get('link');
        $this->assertEquals('Stevie', $link['name']);
        $this->assertEquals('green', $link['color']);
        $this->assertNull($link['empty']);
        $this->assertNull($link['doesnotexist']);
    }

}
?>