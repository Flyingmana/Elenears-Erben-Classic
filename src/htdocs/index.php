<?php
/**
 * Elenears Erben Classic main script
 *
 * This file is part of Elenears Erben Classic.
 *
 *
 * @package Core
 * @license
 */


// Set this to true to see exceptions and their backtraces. Should ALWAYS be
// false in a production environment.
$debug = true;

// @todo: Remove constant
define( 'ARBIT_DEBUG', true );

if ( !defined( 'ARBIT_BASE' ) )
{
    //define( 'ARBIT_BASE', dirname( realpath( __DIR__ ) ) . '/' );
    define( 'ARBIT_BASE', realpath( __DIR__ . "/../../../arbit_src_link/" ) . '/' );
}

if ( !defined( 'EEC_BASE' ) )
{
    //define( 'ARBIT_BASE', dirname( realpath( __DIR__ ) ) . '/' );
    define( 'EEC_BASE', dirname( realpath( __DIR__ ) ) . '/' );
}

if ( $debug )
{
    // Perform envirnonmental checks if debug mode is active. Ensure we are on
    // a correct PHP version and required settings are made in the PHP
    // configuration
    include ARBIT_BASE . 'htdocs/check.php';
}



try
{
    // Set up autoload environment
    require EEC_BASE . 'environment.php';

    // Set up HTTP environment
    $env = new EECHttpEnvironmentDIC();

    // Set up debugging and development options
    $env->debug                 = $debug;
    //$env->functionalTestLogging = true;

    // Initialize file log writer
    $env->log         = ezcLog::getInstance();
    $fileLog          = new ezcLogUnixFileWriter( ARBIT_LOG_PATH, 'error.log' );
    $filter           = new ezcLogFilter();
    $filter->severity = ezcLog::WARNING | ezcLog::ERROR | ezcLog::FATAL;
    $env->log->getMapper()->appendRule(
        new ezcLogFilterRule( $filter, $fileLog, true )
    );

    // Add generic arbit logger for all log messages
    $env->log->getMapper()->appendRule(
        new ezcLogFilterRule( new ezcLogFilter(), new arbitLogger(), true )
    );

    // Set module manager in framework base for autoload handling
    arbitFrameworkBase::setModuleManager( $env->moduleManager );

    // Dispatch request
    $dispatcher = new arbitDispatcher(
        new arbitDispatcherConfiguration( $env )
    );
    $dispatcher->run();
}
catch ( ezcMvcFatalErrorLoopException $e )
{
    // The fatal error loop exception is sometimes thrown, when something goes
    // serioulsy wrong. We exit with an internal server error here, but stiill
    // try to present a readable exception message, if debug mode is turned on.
    try
    {
        // Try to log error, if this fails, ignore the error.
        $env->log->log( $e->getMessage(), ezcLog::FATAL );
    }
    catch ( Exception $e )
    {
        // Ignore, we can't do anything anymore about this.
    }

    // Return a generic HTTP error, so that noone will cache this response
    $http = new arbitHttpServerEnvironment();
    $http->error( 500 );
    echo "<h1>Internal Server Error</h1>\n";

    if ( ARBIT_DEBUG )
    {
        // Show exception only in debug mode
        echo "<pre>$e</pre>";

        $messages = arbitLogger::getMessages();
        if ( isset( $messages[count( $messages ) - 2] ) )
        {
            echo "<h2>Probably caused by:</h2>";
            echo "<pre>", var_dump( $messages[count( $messages ) - 2] ), "</pre>";
        }

        echo "<h2>Full log:</h2>";
        echo "<pre>", var_dump( $messages ), "</pre>";
    }
}
catch ( Exception $e )
{
    // We should nearly never reach this stage, because all exceptions should
    // already be handled in the controller and routing manager.
    //
    // If you get some exception here, most probably there is something
    // seriously fucked up with your setup. There is no need for a nicer
    // message here, as this really should not happen.
    try
    {
        // Try to log error, if this fails, ignore the error.
        $env->log->log( $e->getMessage(), ezcLog::FATAL );
    }
    catch ( Exception $e )
    {
        // Ignore, we can't do anything anymore about this.
    }

    // Return a generic HTTP error, so that noone will cache this response
    $http = new arbitHttpServerEnvironment();
    $http->error( 500 );
    echo "<h1>Internal Server Error</h1>\n";

    if ( $debug )
    {
        // Show exception only in debug mode
        echo "<pre>$e</pre>";
        echo "<h2>Full log:</h2>";
        echo "<pre>", var_dump( arbitLogger::getMessages() ), "</pre>";
    }
}

