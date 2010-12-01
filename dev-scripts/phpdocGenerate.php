#!/usr/bin/env php
<?php

    $dir = realpath( __DIR__ . "/../" );
    chdir( $dir );
    system( 'phpdoc -d ./src/classes/,./src/modules/ -t ./doc/phpdoc/ -i autoload.php' );
    //system( 'phpdoc -s on -ue on -d ./src/classes/,./src/modules/ -t ./doc/phpdoc/ -i autoload.php' );

?>
