<?php
/**
 * EEC view
 *
 * @package Overview
 * @subpackage View
 */

/**
 * Base model struct for the overview index
 *
 * @package Overview
 * @subpackage View
 */
class EECOverviewViewModel extends arbitViewModel
{
    /**
     * Array containing the actual view data.
     *
     * @var array
     */
    protected $properties = array(
        'collection' => null,
    );

    /**
     * Construct project view model from common values
     *
     * @param arbitSourceViewModel $collection
     * @return void
     */
    public function __construct( arbitSourceViewModel $collection = null )
    {
        $this->properties['collection'] = $collection;
    }
}
