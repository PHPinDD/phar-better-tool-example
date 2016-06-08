<?php
/**
 * @author hollodotme
 */

namespace PHPinDD\PharBetterToolExample\Commands\Generate;

use PHPinDD\PharBetterToolExample\Generators\SkeletonCodeGenerator;
use PHPinDD\PharBetterToolExample\Generators\SkeletonCodeGeneratorConfig;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class InitPharBoxProjectCommand
 * @package PHPinDD\PharBetterToolExample\Commands\Generate
 */
final class InitPharBoxProjectCommand extends Command
{
	protected function configure()
	{
		$this->setDescription( 'Initializes an empty phar tool project with box support' );

		$this->addOption( 'force', 'f', InputOption::VALUE_NONE, 'Forces overwrite of directories and files' );

		$this->addArgument( 'targetdir', InputArgument::OPTIONAL, 'Specifies the target dir', WORKING_DIR );
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return int
	 */
	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$logger         = new ConsoleLogger( $output );
		$questionHelper = $this->getHelper( 'question' );

		$vendorNameQuestion         = new Question( "What is your vendor name?\n: ", 'PHPinDD' );
		$projectNameQuestion        = new Question( "What is your project name?\n: ", 'NewPharTool' );
		$projectDescriptionQuestion = new Question( "Describe your project!\n: ", 'Awesome generated tool' );

		$vendorName         = $questionHelper->ask( $input, $output, $vendorNameQuestion );
		$projectName        = $questionHelper->ask( $input, $output, $projectNameQuestion );
		$projectDescription = $questionHelper->ask( $input, $output, $projectDescriptionQuestion );

		$skeletonDir    = PHAR_DIR . '/src/CodeTemplates/PharBoxProject';
		$targetDir      = $input->getArgument( 'targetdir' ) . DIRECTORY_SEPARATOR . $projectName;
		$replacements   = [
			'__VENDOR_NAME__'        => $vendorName,
			'__VENDOR_NAME_LOWER__'  => strtolower( $vendorName ),
			'__PROJECT_NAME__'       => $projectName,
			'__PROJECT_NAME_LOWER__' => strtolower( $projectName ),
			'__DESCRIPTION__'        => $projectDescription,
		];
		$forceOverwrite = (bool)$input->getOption( 'force' );

		$generatorConfig = new SkeletonCodeGeneratorConfig( $skeletonDir, $targetDir, $replacements, $forceOverwrite );
		$generator       = new SkeletonCodeGenerator( $generatorConfig );

		$generator->setLogger( $logger );
		$generator->generate();

		try
		{
			$this->installTools( $logger, $targetDir );
			$this->runComposerUpdate( $logger, $targetDir );
		}
		catch ( ProcessFailedException $e )
		{
			$logger->critical( 'Sub process failed: ' . $e->getMessage() );

			return 1;
		}

		return 0;
	}

	/**
	 * @param LoggerInterface $logger
	 * @param string          $projectDir
	 *
	 * @throws ProcessFailedException
	 */
	private function installTools( LoggerInterface $logger, string $projectDir )
	{
		$logger->info( 'Installing tools...' );

		$process = new Process( 'sh build/tools/update_tools.sh', $projectDir );

		$process->mustRun();

		$logger->info( 'Tools installed.' );
	}

	/**
	 * @param LoggerInterface $logger
	 * @param string          $projectDir
	 *
	 * @throws ProcessFailedException
	 */
	private function runComposerUpdate( LoggerInterface $logger, string $projectDir )
	{
		$logger->info( 'Running composer update...' );

		$process = new Process( 'php build/tools/composer.phar update -o -v', $projectDir );

		$writeBuffer = function ( $type, $buffer )
		{
			echo $buffer;
		};

		$process->mustRun( $writeBuffer );

		$logger->info( 'Composer update finished.' );
	}
}