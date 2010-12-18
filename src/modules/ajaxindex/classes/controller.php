<?php
/**
 * EEC AjaxIndex base controller
 *
 * @package AjaxIndex
 */

/**
 * Base AjaxIndex controller.
 *
 * @package AjaxIndex
 */
class EECModuleAjaxIndexController extends arbitModuleController
{
    /**
     * Index action
     *
     * The index action just dispatches to the default view of the AjaxIndex
     * module
     *
     * @param arbitRequest $request
     * @return arbitViewModuleModel
     */
    public function index( arbitRequest $request )
    {
        $configuration = $this->env->modules->ajaxindex->configuration;
        
        return new arbitViewModuleModel(
            $request->action,
            array(),
            new EECAjaxIndexViewModel(
                $configuration->get( 'customize', 'css' ),
                $configuration->get( 'customize', 'js' )
            )
        );
    }

    /**
     * output all available modules in this project
     *
     *
     * @param arbitRequest $request
     * @return arbitViewModuleModel
     */
    public function getModules( arbitRequest $request)
    {
        //@todo implement
    }

}

