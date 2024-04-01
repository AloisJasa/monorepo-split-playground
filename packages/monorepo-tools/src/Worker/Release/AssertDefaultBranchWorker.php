<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;

final class AssertDefaultBranchWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{		$this->assertCurrentBranch($this->getDefaultBranch());
	}


	public function getDescription(Version $version): string
	{
		return sprintf('Checkout default branch "%s".', $this->getDefaultBranch());
	}
}
