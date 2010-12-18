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
class EECModuleAjaxIndexDIC extends arbitDependencyInjectionContainer
{
    /**
     * Array with names of objects, which are always shared inside of this DIC 
     * instance.
     * 
     * @var array(string => true)
     */
    protected $alwaysShared = array(
        'configuration' => true,
        'facades'       => true,
        'models'        => true,
        'views'         => true,
    );

    /**
     * Initialize DIC values
     * 
     * @return void
     */
    public function initialize()
    {
        $this->configuration = function( $dic )
        {
            return $dic->env->configuration->module(
                $dic->env->request->controller,
                $dic->env->request->action,
                'EECAjaxIndexBackendIniModuleConfiguration'
            );
        };

        $this->views = function( $dic )
        {
            $views = new EECModuleAjaxIndexViewDIC();
            $views->env = $dic->env;
            return $views;
        };
    }
}

