<?php
/**
 * EEC dependency injection container
 *
 * @package Core
 */

/**
 * EEC dependency injection container
 *
 * Loosely based on the example DIC by Fabien Potencier, as described here:
 * http://fabien.potencier.org/article/17/on-php-5-3-lambda-functions-and-closures
 *
 * @package Core
 */
class EECEnvironmentDIC extends arbitEnvironmentDIC
{
    /**
     * List of properties of this DIC instance.
     *
     * Actually this is a list of closures instantiating the objects, which
     * will be retrieved from the DIC.
     *
     * @var array(Closure)
     */
    protected $objects = array(
        'debug'                 => false,
        'functionalTestLogging' => false,
    );

    /**
     * Array with names of objects, which are always shared inside of this DIC
     * instance.
     *
     * @var array(string => true)
     */
    protected $alwaysShared = array(
        'cache'                 => true,
        'messenger'             => true,
        'request'               => true,
        'session'               => true,
        'translationBackend'    => true,
        'translationManager'    => true,
        'commandRegistry'       => true,
        'facades'               => true,
        'controller'            => true,
        'models'                => true,
        'views'                 => true,
        'modules'               => true,
        'log'                   => true,
        'configuration'         => true,
        'moduleManager'         => true,
        'signalSlotHandler'     => true,
    );

    /**
     * Initialize DIC values
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();


        $this->configuration = function( $dic )
        {
            return new EECConfigurationDIC();
        };

        $this->moduleManager = function( $dic )
        {
            $manager = new EECModuleManager( $dic->signalSlotHandler, $dic->log );
            $manager->setEnv( $dic );
            arbitFrameworkBase::setModuleManager( $manager );

            $manager->registerModule( $dic, 'core' );
            foreach ( $dic->configuration->main->modules as $module )
            {
                $manager->registerModule( $dic, $module );
            }

            return $manager;
        };
    }
}

