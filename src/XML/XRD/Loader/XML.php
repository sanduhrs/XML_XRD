<?php
/**
 * Part of XML_XRD
 * 
 * PHP Version 8.0
 *
 * @category XML
 * @package  XML_XRD
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL
 * @link     http://pear.php.net/package/XML_XRD
 */

/**
 * Loads XRD data from an XML file
 *
 * @category XML
 * @package  XML_XRD
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/XML_XRD
 */
class XML_XRD_Loader_XML
{

    /**
     * Data storage the XML data get loaded into
     *
     * @var XML_XRD
     */
    protected XML_XRD $xrd;

    /**
     * XRD 1.0 namespace
     */
    const NS_XRD = 'http://docs.oasis-open.org/ns/xri/xrd-1.0';

    /**
     * Init object with xrd object
     *
     * @param XML_XRD $xrd Data storage the XML data get loaded into
     */
    public function __construct(XML_XRD $xrd)
    {
        $this->xrd = $xrd;
    }

    /**
     * Loads the contents of the given file
     *
     * @param string $file Path to an XRD file
     *
     * @return void
     *
     * @throws XML_XRD_Loader_Exception When the XML is invalid or cannot be
     *                                   loaded
     */
    public function loadFile(string $file): void
    {
        $old = libxml_use_internal_errors(true);
        $x = simplexml_load_file($file);
        libxml_use_internal_errors($old);
        if ($x === false) {
            throw new XML_XRD_Loader_Exception(
                'Error loading XML file: ' . libxml_get_last_error()->message,
                XML_XRD_Loader_Exception::LOAD
            );
        }
        $this->load($x);
    }

    /**
     * Loads the contents of the given string
     *
     * @param string $xml XML string
     *
     * @return void
     *
     * @throws XML_XRD_Loader_Exception When the XML is invalid or cannot be
     *                                   loaded
     */
    public function loadString(string $xml): void
    {
        if ($xml == '') {
            throw new XML_XRD_Loader_Exception(
                'Error loading XML string: string empty',
                XML_XRD_Loader_Exception::LOAD
            );
        }
        $old = libxml_use_internal_errors(true);
        $x = simplexml_load_string($xml);
        libxml_use_internal_errors($old);
        if ($x === false) {
            throw new XML_XRD_Loader_Exception(
                'Error loading XML string: ' . libxml_get_last_error()->message,
                XML_XRD_Loader_Exception::LOAD
            );
        }
        $this->load($x);
    }

    /**
     * Loads the XML element into the classes' data structures
     *
     * @param SimpleXMLElement $x XML element containing the whole XRD document
     *
     * @return void
     *
     * @throws XML_XRD_Loader_Exception When the XML is invalid
     */
    public function load(SimpleXMLElement $x): void
    {
        $ns = $x->getDocNamespaces();
        if ($ns[''] !== self::NS_XRD) {
            throw new XML_XRD_Loader_Exception(
                'Wrong document namespace', XML_XRD_Loader_Exception::DOC_NS
            );
        }
        if ($x->getName() != 'XRD') {
            throw new XML_XRD_Loader_Exception(
                'XML root element is not "XRD"', XML_XRD_Loader_Exception::DOC_ROOT
            );
        }

        if (isset($x->Subject)) {
            $this->xrd->subject = (string)$x->Subject;
        }
        foreach ($x->Alias as $xAlias) {
            $this->xrd->aliases[] = (string)$xAlias;
        }

        foreach ($x->Link as $xLink) {
            $this->xrd->links[] = $this->loadLink($xLink);
        }

        $this->loadProperties($this->xrd, $x);

        if (isset($x->Expires)) {
            $this->xrd->expires = strtotime($x->Expires);
        }

        $xmlAttrs = $x->attributes('http://www.w3.org/XML/1998/namespace');
        if (isset($xmlAttrs['id'])) {
            $this->xrd->id = (string)$xmlAttrs['id'];
        }
    }

    /**
     * Loads the Property elements from XML
     *
     * @param XML_XRD_PropertyAccess $store Properties data store
     * @param SimpleXMLElement       $x     XML element
     *
     * @return boolean True when all went well
     */
    protected function loadProperties(
        XML_XRD_PropertyAccess $store, SimpleXMLElement $x
    ): bool {
        foreach ($x->Property as $xProp) {
            $store->properties[] = $this->loadProperty($xProp);
        }
        return true;
    }

    /**
     * Create a link element object from XML element
     *
     * @param SimpleXMLElement $x XML link element
     *
     * @return XML_XRD_Element_Link Created link object
     */
    protected function loadLink(SimpleXMLElement $x): XML_XRD_Element_Link
    {
        $link = new XML_XRD_Element_Link();
        foreach (['rel', 'type', 'href', 'template'] as $var) {
            if (isset($x[$var])) {
                $link->$var = (string)$x[$var];
            }
        }

        foreach ($x->Title as $xTitle) {
            $xmlAttrs = $xTitle->attributes('http://www.w3.org/XML/1998/namespace');
            $lang = '';
            if (isset($xmlAttrs['lang'])) {
                $lang = (string)$xmlAttrs['lang'];
            }
            if (!isset($link->titles[$lang])) {
                $link->titles[$lang] = (string)$xTitle;
            }
        }
        $this->loadProperties($link, $x);

        return $link;
    }

    /**
     * Create a property element object from XML element
     *
     * @param SimpleXMLElement $x XML property element
     *
     * @return XML_XRD_Element_Property Created link object
     */
    protected function loadProperty(SimpleXMLElement $x): XML_XRD_Element_Property
    {
        $prop = new XML_XRD_Element_Property();
        if (isset($x['type'])) {
            $prop->type = (string)$x['type'];
        }
        $s = (string)$x;
        if ($s != '') {
            $prop->value = $s;
        }

        return $prop;
    }
}
?>