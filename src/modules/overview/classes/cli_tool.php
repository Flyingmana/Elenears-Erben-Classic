<?php
/**
 * CLI tool class for administrative tasks
 *

 *
 * @package Overview
 */

/**
 * CLI tool class, which handles various adimistrative tasks.
 *
 * @package Overview
 * @todo lot of work left
 */
class arbitModulePhpDocAdminCliTool extends arbitFrameworkActionCliTool
{
    /**
     * Name of CLI tool
     *
     * @var string
     */
    protected $name = 'EEC overview module management';

    /**
     * Short description of the purpose of the current CLI tool
     *
     * @var string
     */
    protected $description = "EEC overview module management script.\n\nThe available actions are:";

    /**
     * Available actions.
     *
     * Mapping of the CLI action identifiers to their action names
     *
     * @var array
     */
    protected $actions = array(
        'regenerate' => array(
            'action'      => 'collect',
            'description' => 'collect datas from other modules for index display',
        ),
    );

    /**
     * Get controller
     *
     * Return controller to execute for the current command
     *
     * @param arbitRequest $request
     * @param array $options
     * @return arbitController
     */
    protected function createController( arbitRequest $request, array $options )
    {
        // Find name of phpdoc module
        $config = $this->env->configuration->project( $request->controller );

        $action             = $this->env->in->argumentDefinition['action']->value;
        $request->action    = arbitProjectController::normalizeModuleName(
            array_search( "phpdoc", $config->modules )
        );
        $request->subaction = $this->actions[$action]['action'];

        return new arbitModulePhpDocController( $this->env, $config );
    }
}

