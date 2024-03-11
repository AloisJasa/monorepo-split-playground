<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;

final class AddReleaseTagWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		$this->processRunner->run('git tag ' . $version->getOriginalString());
		$this->assertTagExists($version->getOriginalString());
	}


	public function getDescription(Version $version): string
	{
		return sprintf('Add local tag "%s"', $version->getOriginalString());
	}
}
