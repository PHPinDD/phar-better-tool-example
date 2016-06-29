<?php
/**
 * @author h.woltersdorf
 */

namespace PHPinDD\PharBetterToolExample\Commands\Update;

use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Herrera\Phar\Update\Update;
use Herrera\Version\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SelfUpdate
 * @package PHPinDD\PharBetterToolExample\Commands\Update
 */
final class SelfUpdate extends Command
{
	const MANIFEST_FILE = 'https://raw.githubusercontent.com/PHPinDD/phar-better-tool-distribution/master/manifest.json';

	protected function configure()
	{
		$this->setDescription( 'Updates this PHAR' );
	}

	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$currentVersion = Parser::toVersion( $this->getApplication()->getVersion() );

		echo sprintf(
			"Current version: %s.%s.%s\n",
			$currentVersion->getMajor(),
			$currentVersion->getMinor(),
			$currentVersion->getPatch()
		);

		$manifest = Manifest::loadFile( self::MANIFEST_FILE );
		$update   = $manifest->findRecent( $currentVersion );

		if ( !is_null( $update ) )
		{
			$update = new Update(
				$update->getName(), $update->getSha1(), $update->getUrl(), $update->getVersion(),
				__DIR__ . '/symfony.phar.pubkey'
			);

			$recentVersion = $update->getVersion();

			echo sprintf(
				"Latest version: %s.%s.%s\n",
				$recentVersion->getMajor(),
				$recentVersion->getMinor(),
				$recentVersion->getPatch()
			);

			echo "Updating...";

			$manager = new Manager( $manifest );
			$manager->update( $currentVersion );

			echo " Done.\n";
		}
		else
		{
			echo "Already up-to-date.\n";
		}

		return 0;
	}

}