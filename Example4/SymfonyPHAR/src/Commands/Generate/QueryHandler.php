<?php
/**
 * @author hollodotme
 */

namespace PHPinDD\PharBetterToolExample\Commands\Generate;

use PHPinDD\PharBetterToolExample\Generators\SkeletonCodeGenerator;
use PHPinDD\PharBetterToolExample\Generators\SkeletonCodeGeneratorConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class QueryHandler
 * @package PHPinDD\PharBetterToolExample\Commands\Generate
 */
final class QueryHandler extends Command
{
	protected function configure()
	{
		$this->setDescription( 'Generates a request handler with a query and a query handler' );

		# Options
		$this->addOption( 'target', 't', InputOption::VALUE_OPTIONAL, 'Specifies the target dir', '.' );
		$this->addOption( 'force', 'f', InputOption::VALUE_NONE, 'Forces overwrite of directories and files' );

		# Arguments
		$this->addArgument( 'folder', InputArgument::REQUIRED, 'Specifies the folder in the target dir' );
		$this->addArgument( 'query', InputArgument::REQUIRED, 'Specifies the query name' );
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return int
	 */
	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$logger = new ConsoleLogger( $output );

		$skeletonDir = PHAR_DIR . '/src/CodeTemplates/QueryHandler';
		$targetDir   = $input->getOption( 'target' );

		$replacements = [
			'__FOLDER__' => $input->getArgument( 'folder' ),
			'__QUERY__'  => $input->getArgument( 'query' ),
		];

		$forceOverwrite = (bool)$input->getOption( 'force' );

		$config = new SkeletonCodeGeneratorConfig( $skeletonDir, $targetDir, $replacements, $forceOverwrite );

		$skeletonGenerator = new SkeletonCodeGenerator( $config );
		$skeletonGenerator->setLogger( $logger );

		$skeletonGenerator->generate();

		return 0;
	}
}