<?php
/**
 * EEC signal struct
 *
 *
 * @package Overview
 */

/**
 * Overview module collect signal struct
 *
 * @package Overview
 */
class EECOverviewCollectStruct extends arbitSignalSlotStruct implements arbitUnviewableSignalSlotStruct
{
    /**
     * Array containing the structs properties.
     *
     * @var array
     */
    protected $properties = array(
        'elements'      => null,
    );

    /**
     * add an Element to the collect struck
     *
     * @param EECOverviewCollectElementStruct $element
     * @return void
     */
    public function addElement(EECOverviewCollectElementStruct $element )
    {
        $this->elements[]  = $element;
    }
}

