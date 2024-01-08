<?php
/**
 * Part of XML_XRD
 *
 * PHP version 5
 *
 * @category XML
 * @package  XML_XRD
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL
 * @link     http://pear.php.net/package/XML_XRD
 */

/**
 * Property element in a XRD document.
 *
 * The <XRD> root element as well as <Link> tags may have <Property> children.
 *
 * @category XML
 * @package  XML_XRD
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/XML_XRD
 */
class XML_XRD_Element_Property
{
    /**
     * Value of the property.
     *
     * @var string|null
     */
    public string|null $value;

    /**
     * Type of the propery.
     *
     * @var string|null
     */
    public string|null $type;

    /**
     * Create a new instance
     *
     * @param string|null $type  String representing the property type
     * @param string|null $value Value of the property, may be NULL
     */
    public function __construct(string|null $type = null, string|null $value = null)
    {
        $this->type  = $type;
        $this->value = $value;
    }
}

?>