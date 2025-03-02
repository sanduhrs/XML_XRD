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

require_once 'XML/XRD/LogicException.php';
require_once 'XML/XRD/Element/Property.php';

/**
 * Provides ArrayAccess to extending classes (XML_XRD and XML_XRD_Element_Link).
 *
 * By extending PropertyAccess, access to properties is possible with
 * "$object['propertyType']" array access notation.
 *
 * @category XML
 * @package  XML_XRD
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/XML_XRD
 */
abstract class XML_XRD_PropertyAccess implements ArrayAccess
{

    /**
     * Array of property objects
     *
     * @var array
     */
    public array $properties = [];


    /**
     * Check if the property with the given type exists
     *
     * Part of the ArrayAccess interface
     *
     * @param mixed $type Property type to check for
     *
     * @return boolean True if it exists
     */
    public function offsetExists(mixed $type): bool
    {
        foreach ($this->properties as $prop) {
            if ($prop->type == $type) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return the highest ranked property with the given type
     *
     * Part of the ArrayAccess interface
     *
     * @param mixed $type Property type to check for
     *
     * @return mixed Property value or NULL if empty
     */
    public function offsetGet(mixed $type): mixed
    {
        foreach ($this->properties as $prop) {
            if ($prop->type == $type) {
                return $prop->value;
            }
        }
        return null;
    }

    /**
     * Not implemented.
     *
     * Part of the ArrayAccess interface
     *
     * @param mixed $type  Property type to check for
     * @param mixed $value New property value
     *
     * @return void
     *
     * @throws XML_XRD_LogicException Always
     */
    public function offsetSet(mixed $type, mixed $value): void
    {
        throw new XML_XRD_LogicException('Changing properties not implemented');
    }

    /**
     * Not implemented.
     *
     * Part of the ArrayAccess interface
     *
     * @param mixed $type Property type to check for
     *
     * @return void
     *
     * @throws XML_XRD_LogicException Always
     */
    public function offsetUnset(mixed $type): void
    {
        throw new XML_XRD_LogicException('Changing properties not implemented');
    }

    /**
     * Get all properties with the given type
     *
     * @param string|null $type Property type to filter by
     *
     * @return array Array of XML_XRD_Element_Property objects
     */
    public function getProperties(string|null $type = null): array
    {
        if ($type === null) {
            return $this->properties;
        }
        $properties = [];
        foreach ($this->properties as $prop) {
            if ($prop->type == $type) {
                $properties[] = $prop;
            }
        }
        return $properties;
    }
}
?>