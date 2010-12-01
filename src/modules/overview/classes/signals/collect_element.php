<?php
/**
 * EEC signal struct
 *
 *
 * @package Overview
 */

/**
 * Overview module collect element signal struct
 *
 * @package Overview
 */
class EECOverviewCollectElementStruct extends arbitSignalSlotStruct implements arbitUnviewableSignalSlotStruct
{
    /**
     * Array containing the structs properties.
     *
     * @var array
     */
    protected $properties = array(
        'text'          => null,
        'warning'       => null,
    );

    /**
     * Construct signal struct from its major data fields.
     *
     * @param string $text
     * @param bool $warning
     * @return void
     */
    public function __construct( $text = null, $warning = null )
    {
        $this->text    = $text;
        $this->warning = $warning;
    }
}

