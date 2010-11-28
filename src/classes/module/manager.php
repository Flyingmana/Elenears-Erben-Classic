<?php
/**
 * EEC modules
 *
 *
 * @package Core
 * @subpackage Module
 */

/**
 * Module manager, which takes care of proper module registration.
 *
 * @package Core
 * @subpackage Module
 */
class EECModuleManager extends arbitModuleManager
{
    /**
     * Current environment
     *
     * @var arbitEnvironmentDIC
     */
    protected $env;

    /**
     * Registers an available module.
     *
     * Registers a module identifier that is available for the arbit installation.
     *
     * @param arbitEnvironmentDIC $env 
     * @param string $moduleName
     * @return void
     */
    public function registerModule( arbitEnvironmentDIC $env, $moduleName )
    {
        $this->availableModules[$moduleName] = true;

        // Init module
        $moduleClassName = $this->findModuleClass( $moduleName );
        $this->modules[$moduleName] = new $moduleClassName();

        // Clear module autoload cache
        arbitFrameworkBase::clearAutoloadCache();

        // Register module signals and slots at the core
        $env->signalSlotHandler->registerSignals(
            $moduleName,
            $this->modules[$moduleName]->signals
        );

        // Register module DIC
        if ( $dic = $this->modules[$moduleName]->getModuleDIC() )
        {
            $dic->env                  = $env;
            $env->modules->$moduleName = $dic;
        }

    }

    /**
     * Activates a module
     *
     * Activates a module in core, just given by its name.
     *
     * @param arbitEnvironmentDIC $env 
     * @param string $moduleName
     * @return void
     * @throws arbitUnknownModuleException When an unregistered module is requested.
     */
    public function activateModule( arbitEnvironmentDIC $env, $moduleName )
    {
        if ( !isset( $this->availableModules[$moduleName] ) )
        {
            throw new arbitUnknownModuleException( $moduleName );
        }

        $env->signalSlotHandler->registerSlots(
            $this->modules[$moduleName]->slots
        );

        $this->log->log( "Activated module $moduleName.", ezcLog::INFO );

        // Initialize module
        $this->modules[$moduleName]->initializeModule( $env );
    }

    /**
     * Get all autoload definitions
     *
     * Returns the autoload definition array for all registerd modules. The
     * array is merged from all modules and contains the definition in commonly
     * used structure.
     *
     * @return array
     */
    public function getAutoloads()
    {
        // Build autoload array from all registered modules
        $autoload = array();
        foreach ( $this->modules as $definition )
        {
            foreach ( $definition->autoload as $class => $file )
            {
                $autoload[$class] = $file;
            }
        }

        return $autoload;
    }

    /**
     * Get template paths
     *
     * Get a plain array of all module template paths.
     *
     * @return array
     */
    public function getTemplatePaths()
    {
        $paths = array();
        foreach ( $this->modules as $name => $definition )
        {
            if ( $definition->path !== null )
            {
                $paths[] = $definition->path . '/' . $definition->templateDirectory;
            }
        }

        return $paths;
    }

    /**
     * Set the env.
     *
     * This is intended to make the $env availabil in this class
     *
     * @param arbitEnvironmentDIC $locator
     * @return void
     */
    public function setEnv( arbitEnvironmentDIC $env )
    {
        $this->env = $env;
    }

    /**
     * Find the module definition class in the filesystem.
     *
     * The module definition struct must be located under
     * modules/<name>/definition.php. This method checks if such a file is
     * available and includes the definition struct.
     *
     * @param string $moduleName
     * @return void
     */
    protected function findModuleClass( $moduleName )
    {
        // Initialize locator, if not set from external
        if ( $this->locator === null )
        {
            $this->locator = new EECModuleDefinitionLocator( $this->env );
        }

        return $this->locator->findModuleClass( $moduleName );
    }
}

