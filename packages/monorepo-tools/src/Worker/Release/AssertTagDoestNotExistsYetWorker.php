<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;

final class AssertTagDoestNotExistsYetWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		$this->assertTagDoesNotExists($this->prepareReleaseTagName($version->getOriginalString()));
		$this->assertTagDoesNotExists($version->getOriginalString());
	}


	public function getDescription(Version $version): string
	{
		return sprintf('Assert tags absence.');
	}
}
