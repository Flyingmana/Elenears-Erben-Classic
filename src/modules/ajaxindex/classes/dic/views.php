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
class EECModuleAjaxIndexViewDIC extends arbitDependencyInjectionContainer
{
    /**
     * Array with names of objects, which are always shared inside of this DIC 
     * instance.
     * 
     * @var array(string => true)
     */
    protected $alwaysShared = array(
        'xhtml' => true,
    );

    /**
     * Initialize DIC values
     * 
     * @return void
     */
    public function initialize()
    {
        $this->xhtml = function( $dic )
        {
            $result = new EECModuleAjaxIndexXHtmlHandler(
                $dic->env->request,
                $dic->env->templateConfiguration,
                $dic->env->views->decorator,
                $dic->env,
                $dic->env->configuration->main->get( 'layout', 'css' )
            );
            return $result;
        };
    }
}

