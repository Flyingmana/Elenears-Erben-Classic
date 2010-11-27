<?php
/**
 * EEC dependency injection container
 *
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
class EECConfigurationDIC extends arbitConfigurationDIC
{
    /**
     * Array with names of objects, which are always shared inside of this DIC
     * instance.
     *
     * @var array(string => true)
     */
    protected $alwaysShared = array(
        'iniConfigurationManager' => true,
        'main'                    => true,
    );


    /**
     * Initialize DIC values
     * 
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->main = function( $dic )
        {
            return new EECBackendIniMainConfiguration( $dic->iniConfigurationManager );
        };
    }
}

