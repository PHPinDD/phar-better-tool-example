<?php
/**
 * @author hollodotme
 */

namespace PHPinDD\PharBetterToolExample\Commands\Basic;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SayHello
 * @package PHPinDD\PharBetterToolExample\Commands
 */
final class SayHello extends Command
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