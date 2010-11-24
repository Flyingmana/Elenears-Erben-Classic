<?php
/**
 * EEC base environment setup
 *
 * This file is part of EEC.
 *
 * @package Core
 */

// Set constants with paths to the arbit/EEC files.
//
// If you want to use a different directory structure you may set these
// constants by yourself in your index.php or similar. These constants will
// only be set to their default values, if not declared elsewhere.
if ( !defined( 'ARBIT_BASE' ) )
{
    //define( 'ARBIT_BASE', dirname( realpath( __DIR__ ) ) . '/' );
    define( 'ARBIT_BASE', realpath( __DIR__ . "/../../arbit_src_link/" ) . '/' );
}

if ( !defined( 'EEC_BASE' ) )
{
    //define( 'ARBIT_BASE', dirname( realpath( __DIR__ ) ) . '/' );
    define( 'EEC_BASE',   __DIR__ . '/' );
}

if ( !defined( 'ARBIT_HTDOCS' ) )
{
    define( 'ARBIT_HTDOCS',       EEC_BASE . 'htdocs/' );
}

if ( !defined( 'ARBIT_TMP_PATH' ) )
{
    define( 'ARBIT_TMP_PATH',     EEC_BASE . 'tmp/' );
}

if ( !defined( 'ARBIT_LOG_PATH' ) )
{
    define( 'ARBIT_LOG_PATH',     EEC_BASE . 'var/log/' );
}

if ( !defined( 'ARBIT_CACHE_PATH' ) )
{
    define( 'ARBIT_CACHE_PATH',   EEC_BASE . 'var/cache/' );
}

if ( !defined( 'ARBIT_STORAGE_PATH' ) )
{
    define( 'ARBIT_STORAGE_PATH', EEC_BASE . 'var/lib/' );
}

// define( 'ARBIT_CONFIG', ARBIT_BASE . '../tests/data/config/' );
if ( !defined( 'ARBIT_CONFIG' ) )
{
    define( 'ARBIT_CONFIG',       EEC_BASE . 'config/' );
}

// Default to debugging switched off, if not set to something else before.
if ( !defined( 'ARBIT_DEBUG' ) )
{
    define( 'ARBIT_DEBUG',      false );
}

// Always required for autoloading
require ARBIT_BASE . 'environment.php';