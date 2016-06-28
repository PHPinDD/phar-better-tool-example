<?php

$phar = new Phar( __DIR__ . '/hello.phar' );
$phar->buildFromDirectory( __DIR__ . '/HelloPHAR' );
$phar->setDefaultStub( 'bin/main.php' );
