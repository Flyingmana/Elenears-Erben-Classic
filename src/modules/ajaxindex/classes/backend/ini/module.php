<?php
/**
 * EEC ini configuration backend
 *
 * @package Source
 * @subpackage IniBackend
 */

/**
 * EEC project configuration
 *
 * @package AjaxIndex
 * @subpackage IniBackend
 */
class EECAjaxIndexBackendIniModuleConfiguration extends arbitBackendIniModuleConfiguration
{
    /**
     * Array with default values for all required configuration directives. The
     * array has the following structure:
     *
     * <code>
     *  array(
     *      'group' => array(
     *          'key' => $value,
     *          ...
     *      ),
     *      ...
     *  )
     * </code>
     *
     * @var array
     */
    protected $defaultValues = array(
        'customize' => array(
            'css'       => array(),
            'js'        => array(),
        ),
    );

    /**
     * Shortcuts for configuration value access.
     *
     * These shortcuts may be used for property access to the configuration
     * values. The shortcut array has the following structure:
     *
     * <code>
     *  array(
     *      'shortcut' => array( 'group', 'value' ),
     *      ...
     *  )
     * </code>
     *
     * @var array
     */
    protected $shortcuts = array(
    );
}

