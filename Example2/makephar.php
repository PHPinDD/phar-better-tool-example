<?php

$phar = new Phar( __DIR__ . '/console.phar' );
$phar->buildFromDirectory( __DIR__ . '/ConsolePHAR' );

$phar->setStub( fopen( __DIR__ . '/stub.php', 'r' ) );
