<?php
/**
 * EEC ini configuration backend
 *
 *
 * @package Core
 * @subpackage IniBackend
 */

/**
 * EEC main configuration
 *
 * @package Core
 * @subpackage IniBackend
 */
class EECBackendIniMainConfiguration extends arbitBackendIniMainConfiguration
{
    /**
     * Array with default values for all required configuration directives. The
     * array has the followin structure:
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
        'main' => array(
            'email'         => false,
            'language'      => 'en',
        ),
        'backend' => array(
            'type'          => 'couchdb',
            'connection'    => 'couchdb://localhost:5984/eec_',
        ),
        'layout' => array(
            'css'           => array(),
            'override'      => array(),
        ),
        'modules' => array(
            'module'        => array(),
        ),
        'projects' => array(
            'project'       => array(),
        ),
        'mail' => array(
            'transport'     => 'mta',
            'options'       => array(),
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
        'email'         => array( 'main', 'email' ),
        'language'      => array( 'main', 'language' ),

        'backendType'   => array( 'backend', 'type' ),
        'backendUrl'    => array( 'backend', 'connection' ),

        'modules'       => array( 'modules', 'module' ),
        'projects'      => array( 'projects', 'project' ),
    );

    /**
     * Construct from ezc configuration manager
     *
     * @param ezcConfigurationManager $config
     * @return void
     */
    public function __construct( ezcConfigurationManager $config )
    {
        $this->iniFile = 'main';

        parent::__construct( $config );
    }
}

