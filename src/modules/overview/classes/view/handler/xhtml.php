<?php
/**
 * EEC view handler
 *
 *
 * @package Overview
 * @subpackage View
 */

/**
 * XHtml view handler generating XHtml from the provided view model.
 *
 * @package Overview
 * @subpackage View
 */
class EECModuleOverviewXHtmlHandler extends arbitViewXHtmlHandler
{
    /**
     * Method template association for default calls.
     *
     * In most cases we just assign some template to a view more, for which a
     * method on this class is called. The normal plain procedure may just be
     * mapped using this array and is handled inside the __call method of this
     * class.
     *
     * For callStatic all method names need to be lowercase.
     *
     * @var array
     */
    protected $templates = array(
        'showIndexModel'    => 'html/index.tpl',
    );
}

