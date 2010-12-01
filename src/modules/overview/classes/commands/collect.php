<?php
/**
 * EEC Overview module collect command
 *
 *
 * @package Overview
 */

/**
 * EEC Overview module collect command
 *
 * @package Overview
 */
class EECModuleOverviewCollectCommand extends arbitScheduledTaskCommand
{
    /**
     * Run command
     *
     * Execute the actual bits.
     *
     * Should return one of the status constant values, defined as class
     * constants in periodicCommand.
     *
     * @return int
     */
    public function run()
    {
        try
        {
            $request    = $this->getRequest( 'overview' );
            $controller = new arbitModuleOverviewController( $this->env, $this->env->configuration->project( $request->controller ) );
            $controller->generate( $request );
            return periodicExecutor::SUCCESS;
        }
        catch ( Exception $e )
        {
            $this->logger->log( $e->getMessage(), periodicLogger::ERROR );
            return periodicExecutor::ERROR;
        }
    }
}

