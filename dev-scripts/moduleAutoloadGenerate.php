#!/usr/bin/env php
<?php

    $dir = realpath( __DIR__ . "/../src/modules/" );
    chdir( $dir );
    $modules = scandir( "./" );
    // exclude some unwanted paths
    $modules = array_diff(
        $modules,
        array(
            '.',
            '..'
        )
    );
    foreach( $modules as $path ){
        chdir( $dir . '/' . $path);
        system( 'phpab -o ./autoload.php ./' );
    }

?>
