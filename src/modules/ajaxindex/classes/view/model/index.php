<?php
/**
 * EEC view
 *
 * @package AjaxIndex
 * @subpackage View
 */

/**
 * Base model struct for the overview index
 *
 * @package AjaxIndex
 * @subpackage View
 */
class EECAjaxIndexViewModel extends arbitViewModel
{
    /**
     * Array containing the actual view data.
     *
     * @var array
     */
    protected $properties = array(
        'cssLinks'   => array(),
        'jsLinks'    => array(),
    );

    /**
     * Construct project view model from common values
     *
     * @param arbitSourceViewModel $collection
     * @return void
     */
    public function __construct( array $cssLinks, array $jsLinks)
    {
        $this->properties['cssLinks'] = $cssLinks;
        $this->properties['jsLinks']  = $jsLinks;
    }


    /**
     * Add CSS files
     *
     * Add additional CSS files linked in HTML output.
     *
     * @param string $location
     * @return void
     */
    public function addCssLink( $location )
    {
        array_push( $this->properties['cssLinks'], $location );
    }



    /**
     * Add JS files
     *
     * Add additional JS files linked in HTML output.
     *
     * @param string $location
     * @return void
     */
    public function addJsLink( $location )
    {
        array_push( $this->properties['jsLinks'], $location );
    }
}
