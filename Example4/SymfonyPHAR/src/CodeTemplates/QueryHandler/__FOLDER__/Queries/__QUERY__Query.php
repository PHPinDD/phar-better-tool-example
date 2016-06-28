<?php
/**
 * @author PharBetterToolExample
 */

namespace PHPinDD\PharBetterToolExample\__FOLDER__\Queries;

/**
 * Class __QUERY__Query
 * @package PHPinDD\PharBetterToolExample\__FOLDER__\Queries
 */
final class __QUERY__Query
{
	/** @var array */
	private $requestData;

	public function __construct( array $requestData )
	{
		$this->requestData = $requestData;
	}

	public function get( string $key )
	{
		return $this->requestData[ $key ] ?? null;
	}
}