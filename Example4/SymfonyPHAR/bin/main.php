<?php declare(strict_types = 1);
/**
 * @author hwoltersdorf
 */

namespace PHPinDD\PharBetterToolExample;

use PHPinDD\PharBetterToolExample\Commands\Basic\PrintConstants;
use PHPinDD\PharBetterToolExample\Commands\Basic\SayHello;
use PHPinDD\PharBetterToolExample\Commands\Convert\Markdown2Html;
use PHPinDD\PharBetterToolExample\Commands\Generate\InitPharBoxProject;
use PHPinDD\PharBetterToolExample\Commands\Generate\QueryHandler;
use PHPinDD\PharBetterToolExample\Commands\Update\SelfUpdate;
use Symfony\Component\Console\Application;

error_reporting( -1 );
ini_set( 'display_errors', '1' );

require_once(__DIR__ . '/../../../vendor/autoload.php');

define( 'PHAR_DIR', dirname( __DIR__ ) );
define( 'WORKING_DIR', getcwd() );

$app = new Application( 'PHAR better tool', '@package_version@' );

try
{
	$app->add( new SayHello( 'basic:say-hello' ) );
	$app->add( new PrintConstants( 'basic:print-constants' ) );

	$app->addCommands(
		[
			new QueryHandler( 'generate:query-handler' ),
			new InitPharBoxProject( 'generate:phar-box-project' ),
			new Markdown2Html( 'convert:markdown2html' ),
			new SelfUpdate( 'self-update' ),
		]
	);

	$exitCode = $app->run();
}
catch ( \Throwable $e )
{
	echo get_class( $e ), " thrown\nwith message: ", $e->getMessage(), "\ntrace:\n", $e->getTraceAsString();
	$exitCode = 1;
}

exit($exitCode);
