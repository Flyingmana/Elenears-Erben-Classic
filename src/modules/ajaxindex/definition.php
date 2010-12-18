<?php
/**
 * EEC AjaxIndex module definition
 *
 *
 * @package AjaxIndex
 */

/**
 * EEC Source module definition
 *
 * @package AjaxIndex
 */
class EECModuleAjaxIndexDefintion extends arbitModuleDefintion
{
    /**
     * Array containing the module structures properties.
     *
     * Take a look at the property descriptions in the class level
     * documentation for a more detailed description for each of the
     * properties.
     *
     * Do not add any properties to this array, which are not defined in this
     * base class, because those might be used later on by the core.
     *
     * @var array
     */
    protected $properties = array(
        'autoload'          => null,
        'permissions'       => array(
        ),
        'slots'             => array(
        ),
        'signals'           => array(
        ),

        'templateDirectory' => 'templates/',
        'controller'        => 'EECModuleAjaxIndexController',
        'path'              => __DIR__,
    );

    /**
     * List of view models used by the module
     *
     * List of used view handlers associated with a list of view
     * models used by the module, each associated with a callback to
     * the concrete handler implementation to visit the view model.
     *
     * <code>
     *  'arbitViewXHtmlHandler' => array(
     *      'myViewModel' => 'myXHtmlHandler::showMyModel',
     *      ...
     *  ),
     *  ...
     * </code>
     *
     * @var array
     */
    protected $viewModels = array(
        'xhtml' => array(
            'EECAjaxIndexViewModel'    => 'EECModuleAjaxIndexXHtmlHandler::showBaseModel',
        ),
    );

    /**
     * Initialize views
     *
     * @param arbitEnvironmentDIC $env
     * @return void
     */
    protected function initializeViews( arbitEnvironmentDIC $env )
    {
        $viewHandler = array(
            'xhtml' => $env->modules->ajaxindex->views->xhtml,
        );

        foreach ( $this->viewModels as $handlerType => $views )
        {
            $handler = $viewHandler[$handlerType];

            foreach ( $views as $model => $callback )
            {
                // Register configured handler
                $env->views->decorator->addDecorator(
                    $handlerType, $model, array( $handler, $callback )
                );

                // Always register handler for additional context information
                $env->views->decorator->addDecorator(
                    $handlerType, $model, 'addContextInformation'
                );
            }
        }
    }

    /**
     * Get module specific dependency injection container
     *
     * Returns a module specific dependency injection container, if the module
     * requires one.
     *
     * @return arbitDependencyInjectionContainer
     */
    public function getModuleDIC()
    {
        return new EECModuleAjaxIndexDIC();
    }

}

