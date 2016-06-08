<?php
/**
 * @author hollodotme
 */

namespace PHPinDD\PharBetterToolExample\Commands\Convert;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Markdown2HtmlCommand
 * @package PHPinDD\PharBetterToolExample\Commands\Convert
 */
final class Markdown2HtmlCommand extends Command
{
	protected function configure()
	{
		$this->setDescription( 'Converts a markdown file from STDIN into a HTML file to STDOUT' );
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

		if ( 0 === ftell( STDIN ) )
		{
			$content = '';
			while ( !feof( STDIN ) )
			{
				$content .= fread( STDIN, 1024 );
			}

			$parsedown = new \Parsedown();

			fwrite( STDOUT, '<!DOCTYPE html><html lang="en"><head>' );
			fwrite(
				STDOUT,
				'<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">'
			);
			fwrite( STDOUT, '</head><body><div class="container">' );
			fwrite( STDOUT, $parsedown->text( $content ) );
			fwrite( STDOUT, '</div></body></html>' );

			return 0;
		}

		$logger->critical( 'Pipe a markdown file to STDIN' );

		return 1;
	}
}