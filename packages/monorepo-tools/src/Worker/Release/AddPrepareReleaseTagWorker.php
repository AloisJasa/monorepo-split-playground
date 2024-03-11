<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;

final class AddPrepareReleaseTagWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		$prepareReleaseTagName = $this->prepareReleaseTagName($version->getOriginalString());
		$this->processRunner->run('git tag ' . $prepareReleaseTagName);
		$this->assertTagExists($prepareReleaseTagName);
	}


	public function getDescription(Version $version): string
	{
		return sprintf('Add local tag "%s"', $this->prepareReleaseTagName($version->getOriginalString()));
	}
}
