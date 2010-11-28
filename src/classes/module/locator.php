<?php
/**
 * EEC modules
 *
 *
 * @package Core
 * @subpackage Module
 */

/**
 * Module locator, which finds and loads module definitions
 *
 * @package Core
 * @subpackage Module
 */
class EECModuleDefinitionLocator extends arbitModuleDefinitionLocator
{
    /**
     * Current environment
     *
     * @var arbitEnvironmentDIC
     */
    protected $env;

    /**
     * Construct 
     *
     * @param arbitSignalSlot $signalSlotHandler
     * @return void
     */
    public function __construct( arbitEnvironmentDIC $env )
    {
        $this->env = $env;
    }

    /**
     * Get filename for module definition file
     *
     * Get the file name for a module name either from the exception list, if
     * defined there, or by creating the path matching the convention, which
     * points to modules/<name>/definition.php.
     *
     * @param string $moduleName
     * @return string
     */
    protected function getFileName( $moduleName )
    {
        if ( isset( $this->moduleFile[$moduleName] ) )
        {
            return ARBIT_BASE . $this->moduleFile[$moduleName];
        }

        //return ARBIT_BASE . 'modules/' . $moduleName . '/definition.php';
        $modulePath = $dic->env->configuration->project( $dic->env->request->controller )->get( "backend", "modulepath" );
        return $modulePath . $moduleName . '/definition.php';
    }

    /**
     * Get classname for module definition class
     *
     * Get the class name for a module name either from the exception list, if
     * defined there, or by creating the name matching the convention, which
     * is EECModule<Name>Definition
     *
     * @param string $moduleName
     * @return string
     */
    protected function getClassName( $moduleName )
    {
        if ( isset( $this->moduleClass[$moduleName] ) )
        {
            return $this->moduleClass[$moduleName];
        }

        return 'EECModule' . $moduleName . 'Defintion';
    }

    /**
     * Find the module definition class in the filesystem.
     *
     * Locates the file using either the defined conventions or the exception
     * list, and includes the definitions. The checks if the class has been
     * defined, and returns the module definition class name, if successfully
     * found.
     *
     * @param string $moduleName
     * @return string
     */
    public function findModuleClass( $moduleName )
    {
        // Check if class is already available, then exit immediately with
        // class name.
        if ( class_exists( $moduleClassName = $this->getClassName( $moduleName ), false ) )
        {
            return $moduleClassName;
        }

        // Just try to include class and catch PHP errors. Dooing this we do
        // not need to manually scan the include path for the file.
        try
        {
            include $fileName = $this->getFileName( $moduleName );
        }
        catch ( arbitPhpErrorException $e )
        {
            throw new arbitModuleFileNotFoundException( $fileName );
        }

        // Check if class has really been defined
        if ( !class_exists( $moduleClassName, false ) )
        {
            throw new arbitModuleDefinitionNotFoundException( $moduleClassName );
        }

        // Return class name on success
        return $moduleClassName;
    }
}

