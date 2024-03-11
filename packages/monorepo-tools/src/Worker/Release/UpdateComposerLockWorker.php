<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;

final class UpdateComposerLockWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		$this->processRunner->run('composer update --lock && git add composer.lock');
	}


	public function getDescription(Version $version): string
	{
		return 'Update composer.lock to be in sync with composer.json';
	}
}
