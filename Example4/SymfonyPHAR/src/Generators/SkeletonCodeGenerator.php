<?php
/**
 * @author hwoltersdorf
 */

namespace PHPinDD\PharBetterToolExample\Generators;

use Psr\Log\LoggerAwareTrait;

/**
 * Class SkeletonCodeGenerator
 * @package PHPinDD\PharBetterToolExample\Generators
 */
final class SkeletonCodeGenerator
{
	use LoggerAwareTrait;

	/** @var SkeletonCodeGeneratorConfig */
	private $config;

	public function __construct( SkeletonCodeGeneratorConfig $config )
	{
		$this->config = $config;
	}

	public function generate()
	{
		$skeletonDir  = $this->config->getSkeletonDir();
		$targetDir    = $this->config->getTargetDir();
		$replacements = $this->config->getReplacements();
		$search       = array_keys( $replacements );
		$replace      = array_values( $replacements );

		$dirIterator = new \RecursiveDirectoryIterator(
			$skeletonDir, \RecursiveDirectoryIterator::SKIP_DOTS
		);

		$iterator = new \RecursiveIteratorIterator(
			$dirIterator, \RecursiveIteratorIterator::SELF_FIRST
		);

		$skeletonDirQuoted = preg_quote( $skeletonDir, '#' );

		/** @var \SplFileInfo $fileInfo */
		foreach ( $iterator as $fileInfo )
		{
			$pathName = preg_replace( "#^{$skeletonDirQuoted}#", $targetDir, $fileInfo->getPathname() );

			if ( $fileInfo->isFile() )
			{
				$directory = str_replace( $search, $replace, dirname( $pathName ) );
				$this->createDirectory( $directory );

				$targetFilePath = str_replace( $search, $replace, $pathName );

				$this->createFile( $targetFilePath, $fileInfo->getPathname(), $replacements );
			}
		}
	}

	private function createDirectory( string $directory )
	{
		if ( file_exists( $directory ) )
		{
			$this->logger->warning( 'Directory ' . $directory . ' already exists.' );
		}
		else
		{
			@mkdir( $directory, 0755, true );
		}
	}

	private function createFile( string $targetFilePath, string $sourceFilePath, array $replacements )
	{
		$forceOverride = $this->config->forceOverride();
		$fileExists    = file_exists( $targetFilePath );

		if ( $fileExists && !$forceOverride )
		{
			$this->logger->warning( 'File ' . $targetFilePath . ' already exists, skipping.' );

			return;
		}

		$fileContents = file_get_contents( $sourceFilePath );
		$fileContents = str_replace(
			array_keys( $replacements ),
			array_values( $replacements ),
			$fileContents
		);

		$result = file_put_contents( $targetFilePath, $fileContents );

		if ( $result === false )
		{
			if ( $fileExists )
			{
				$this->logger->error( 'Could not overwrite file ' . $targetFilePath );
			}
			else
			{
				$this->logger->error( 'Could not create file ' . $targetFilePath );
			}
		}
		else
		{
			if ( $fileExists )
			{
				$this->logger->warning( 'Overwritten file ' . $targetFilePath );
			}
			else
			{
				$this->logger->info( 'Created file ' . $targetFilePath );
			}
		}
	}
}