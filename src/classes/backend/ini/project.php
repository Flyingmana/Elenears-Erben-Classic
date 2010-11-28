<?php
/**
 * EEC ini configuration backend
 *
 *
 * @package Core
 * @subpackage IniBackend
 */

/**
 * EEC project configuration
 *
 * @package Core
 * @subpackage IniBackend
 */
class EECBackendIniProjectConfiguration extends arbitBackendIniProjectConfiguration
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
        'project' => array(
            'name'          => 'Missing project name',
            'description'   => 'Missing project description.',
            'language'      => 'en',
            'module'        => array(),
        ),
        'backend' => array(
            'facade'        => 'couchdb',
            'connections'   => array(
                'couchdb'   => 'couchdb://localhost:5984/eec_'
            ),
            'modulepath'    => '',
        ),
        'user' => array(
            'auth'          => array(
                'Password'  => 'arbitCoreModuleUserPasswordAuthentification'
            ),
            'administrator' => array(),
        ),
        'mail' => array(
            'transport'     => '',
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
        'name'           => array( 'project', 'name' ),
        'description'    => array( 'project', 'description' ),
        'language'       => array( 'project', 'language' ),
        'modules'        => array( 'project', 'module' ),
        'administrators' => array( 'user', 'administrator' ),
        'auth'           => array( 'user', 'auth' ),
    );

    /**
     * Construct from ezc configuration manager
     *
     * @param ezcConfigurationManager $config
     * @param string $project
     * @return void
     */
    public function __construct( ezcConfigurationManager $config, $project )
    {
        $this->iniFile = $project . '/project';

        parent::__construct( $config );
    }
}

