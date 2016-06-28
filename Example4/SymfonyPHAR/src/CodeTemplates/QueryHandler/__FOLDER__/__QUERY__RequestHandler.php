<?php
/**
 * @author PharBetterToolExample
 */

namespace PHPinDD\PharBetterToolExample\__FOLDER__;

use PHPinDD\PharBetterToolExample\__FOLDER__\Queries\__QUERY__Query;
use PHPinDD\PharBetterToolExample\__FOLDER__\QueryHandlers\__QUERY__QueryHandler;

/**
 * Class __QUERY__RequestHandler
 * @package PHPinDD\PharBetterToolExample\__FOLDER__
 */
final class __QUERY__RequestHandler
{
	public function handle( array $requestData )
	{
		$query   = new __QUERY__Query( $requestData );
		$handler = new __QUERY__QueryHandler();

		$handler->handle( $query );
	}
}