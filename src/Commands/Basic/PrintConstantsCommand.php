<?php
/**
 * @author hollodotme
 */

namespace PHPinDD\PharBetterToolExample\Commands\Basic;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PrintConstantsCommand
 * @package PHPinDD\PharBetterToolExample\Commands
 */
final class PrintConstantsCommand extends Command
{
	protected function configure()
	{
		$this->setDescription( 'Prints the defined constants in bin/main' );
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return int
	 */
	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$output->writeln( 'PHAR_DIR: ' . PHAR_DIR );
		$output->writeln( 'WORKING_DIR: ' . WORKING_DIR );

		return 0;
	}
}