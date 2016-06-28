<?php

$phar = new Phar( __DIR__ . '/web.phar' );
$phar->buildFromDirectory( __DIR__ . '/WebPHAR/' );
$phar->setDefaultStub( null, __DIR__ . '/WebPHAR/index.php' );
