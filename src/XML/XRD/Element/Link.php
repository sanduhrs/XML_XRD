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

require_once 'XML/XRD/PropertyAccess.php';

/**
 * Link element in a XRD file. Attribute access via object properties.
 *
 * Retrieving the title of a link is possible with the getTitle() convenience
 * method.
 *
 * @category XML
 * @package  XML_XRD
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/XML_XRD
 */
class XML_XRD_Element_Link extends XML_XRD_PropertyAccess
{

    /**
     * Link relation
     *
     * @var string|null
     */
    public string|null $rel;

    /**
     * Link type (MIME type)
     *
     * @var string|null
     */
    public string|null $type;

    /**
     * Link URL
     *
     * @var string|null
     */
    public string|null $href = null;

    /**
     * Link URL template
     *
     * @var string|null
     */
    public string|null $template = null;

    /**
     * Array of key-value pairs: Key is the language, value the title
     *
     * @var array
     */
    public array $titles = [];



    /**
     * Create a new instance and load data from the XML element
     *
     * @param string|null $rel        string with the relation name/URL
     * @param string|null $href       HREF value
     * @param string|null $type       Type value
     * @param boolean     $isTemplate When set to true, the $href is
     *                                used as template
     */
    public function __construct(
        string|null $rel = null,
        string|null $href = null,
        string|null $type = null,
        bool $isTemplate = false
    ) {
        $this->rel = $rel;
        if ($isTemplate) {
            $this->template = $href;
        } else {
            $this->href = $href;
        }
        $this->type = $type;
    }

    /**
     * Returns the title of the link in the given language.
     * If the language is not available, the first title without the language
     * is returned. If no such one exists, the first title is returned.
     *
     * @param string|null $lang 2-letter language name
     *
     * @return string|null Link title
     */
    public function getTitle(string|null $lang = null): string|null
    {
        if (count($this->titles) == 0) {
            return null;
        }

        if ($lang == null) {
            return reset($this->titles);
        }

        if (isset($this->titles[$lang])) {
            return $this->titles[$lang];
        }
        if (isset($this->titles[''])) {
            return $this->titles[''];
        }

        //return first
        return reset($this->titles);
    }
}

?>