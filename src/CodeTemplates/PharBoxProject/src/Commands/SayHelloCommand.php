<?php
/**
 * @author PharBetterToolExample
 */

namespace __VENDOR_NAME__\__PROJECT_NAME__\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SayHelloCommand
 * @package __VENDOR_NAME__\__PROJECT_NAME__\Commands
 */
final class SayHelloCommand extends Command
{

	protected function configure()
	{
		$this->setDescription( 'Says hello.' );
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return int
	 */
	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$output->writeln( 'HELLO WORLD!' );

		return 0;
	}
}