<?php
/**
 * EEC Overview module definition
 *
 *
 * @package Overview
 */

/**
 * EEC Source module definition
 *
 * @package Overview
 */
class EECModuleOverviewDefintion extends arbitModuleDefintion
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
            'overviewCollect'     => 'Request infos from other Modules for listing',
        ),

        'templateDirectory' => 'templates/',
        'controller'        => 'EECModuleOverviewController',
        'path'              => __DIR__,
    );

    /**
     * Array with caches registered on initialization
     *
     * Each cache must have a unique name, normally prefixed with the module
     * identifier. The definition array should at least contain a path and a
     * time to live for the cache.
     *
     * <code>
     *  'name' => array(
     *      'path' => 'dir/',
     *      'ttl'  => arbitCache::INFINITE,
     *  ),
     *  ...
     * </code>
     *
     * @var array
     */
    protected $caches = array(
        'overviewInfos' => array(
            'path' => 'overview/infos/',
            'ttl'  => 3600,
        ),
    );

    /**
     * Array with facades registered on initialization
     *
     * Array with facedes for all known database backends linked with their
     * respective implementation.
     *
     * <code>
     *  'couchdb' => array(
     *      'name' => 'class',
     *  ),
     * </code>
     *
     * @var array
     */
    protected $facades = array(
    );

    /**
     * CouchDB documents to be registered at the document manager.
     *
     * @var array
     */
    protected $couchDbDocuments = array(
    );

    /**
     * CouchDB documents to be registered at the document manager.
     *
     * @var array
     */
    protected $couchDbViews = array(
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
            'EECOverviewViewModel'    => 'EECModuleOverviewXHtmlHandler::showIndexModel',
        ),
    );

    /**
     * List of command definitions of the module
     *
     * Array containing command names and their assiciated classes in the module
     * definition.
     *
     * <code>
     *  array(
     *      'mymodule.mycommand' => 'myModuleMyCommand',
     *      ...
     *  )
     * </code>
     *
     * @var array
     */
    protected $commands = array(
        'overview.collect'            => 'arbitModuleOverviewCollectCommand',
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
            'xhtml' => new EECModuleOverviewXHtmlHandler(
                $env->request,
                $env->templateConfiguration,
                $env->views->decorator,
                $env,
                $env->configuration->main->get( 'layout', 'css' )
            ),
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

}

