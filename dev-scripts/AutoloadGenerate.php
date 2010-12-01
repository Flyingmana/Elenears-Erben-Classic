#!/usr/bin/env php
<?php

    $dir = realpath( __DIR__ . "/../src/classes/" );
    chdir( $dir );
    system( 'phpab -o ./autoload.php ./' );

?>
